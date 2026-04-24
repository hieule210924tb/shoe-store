<?php

declare(strict_types=1);

function chat_get_or_create_thread_for_user(int $userId): array
{
  $thread = getOne('SELECT * FROM chat_threads WHERE user_id = ? LIMIT 1', [$userId]);
  if ($thread) {
    return $thread;
  }

  insertRow('chat_threads', [
    'user_id' => $userId,
    'admin_id' => null,
    'status' => 'open',
    'last_message_at' => null,
  ]);

  $id = (int)db()->lastInsertId();
  $thread = getOne('SELECT * FROM chat_threads WHERE id = ? LIMIT 1', [$id]);
  if (!$thread) {
    throw new RuntimeException('Không thể tạo cuộc trò chuyện.');
  }
  return $thread;
}

function chat_get_thread_for_admin(int $threadId): ?array
{
  return getOne(
    'SELECT t.*, u.name AS user_name, u.email AS user_email
     FROM chat_threads t
     JOIN users u ON u.id = t.user_id
     WHERE t.id = ?
     LIMIT 1',
    [$threadId]
  );
}

function chat_list_threads_for_admin(int $limit = 100): array
{
  $limit = max(1, min(200, $limit));
  $sql = '
    SELECT
      t.id,
      t.user_id,
      t.admin_id,
      t.status,
      t.last_message_at,
      t.updated_at,
      u.name AS user_name,
      u.email AS user_email,
      (
        SELECT COUNT(*)
        FROM chat_messages m
        WHERE m.thread_id = t.id
          AND m.sender_role = "user"
          AND m.read_by_admin_at IS NULL
      ) AS unread_for_admin
    FROM chat_threads t
    JOIN users u ON u.id = t.user_id
    ORDER BY COALESCE(t.last_message_at, t.updated_at) DESC
    LIMIT ?
  ';

  $stmt = db()->prepare($sql);
  $stmt->bindValue(1, $limit, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll();
}

function chat_fetch_messages(int $threadId, int $afterId = 0, int $limit = 50): array
{
  $afterId = max(0, $afterId);
  $limit = max(1, min(200, $limit));

  $sql = '
    SELECT id, thread_id, sender_role, sender_id, body, read_by_user_at, read_by_admin_at, created_at
    FROM chat_messages
    WHERE thread_id = :thread_id
      AND id > :after_id
    ORDER BY id ASC
    LIMIT :lim
  ';
  $stmt = db()->prepare($sql);
  $stmt->bindValue(':thread_id', $threadId, PDO::PARAM_INT);
  $stmt->bindValue(':after_id', $afterId, PDO::PARAM_INT);
  $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll();
}

function chat_insert_message(int $threadId, string $senderRole, int $senderId, string $body): int
{
  $senderRole = $senderRole === 'admin' ? 'admin' : 'user';
  $body = trim($body);
  if ($body === '') {
    throw new InvalidArgumentException('Nội dung tin nhắn trống.');
  }
  if (mb_strlen($body) > 2000) {
    throw new InvalidArgumentException('Tin nhắn quá dài (tối đa 2000 ký tự).');
  }

  $data = [
    'thread_id' => $threadId,
    'sender_role' => $senderRole,
    'sender_id' => $senderId,
    'body' => $body,
    'read_by_user_at' => null,
    'read_by_admin_at' => null,
  ];

  // Sender tự xem như đã đọc phía mình.
  if ($senderRole === 'user') {
    $data['read_by_user_at'] = date('Y-m-d H:i:s');
  } else {
    $data['read_by_admin_at'] = date('Y-m-d H:i:s');
  }

  insertRow('chat_messages', $data);
  $msgId = (int)db()->lastInsertId();

  getRows('UPDATE chat_threads SET last_message_at = CURRENT_TIMESTAMP WHERE id = ?', [$threadId]);

  return $msgId;
}

function chat_mark_read_for_admin(int $threadId): void
{
  getRows(
    'UPDATE chat_messages
     SET read_by_admin_at = CURRENT_TIMESTAMP
     WHERE thread_id = ?
       AND sender_role = "user"
       AND read_by_admin_at IS NULL',
    [$threadId]
  );
}

function chat_mark_read_for_user(int $threadId): void
{
  getRows(
    'UPDATE chat_messages
     SET read_by_user_at = CURRENT_TIMESTAMP
     WHERE thread_id = ?
       AND sender_role = "admin"
       AND read_by_user_at IS NULL',
    [$threadId]
  );
}

function chat_assign_admin_if_needed(int $threadId, int $adminId): void
{
  getRows(
    'UPDATE chat_threads
     SET admin_id = COALESCE(admin_id, ?)
     WHERE id = ?',
    [$adminId, $threadId]
  );
}

