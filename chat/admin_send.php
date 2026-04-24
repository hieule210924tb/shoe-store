<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

header('Content-Type: application/json; charset=utf-8');

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new RuntimeException('Method not allowed');
  }

  $adminId = current_user_id();
  if (!$adminId) {
    throw new RuntimeException('Chưa đăng nhập.');
  }

  $threadId = get_int('thread_id', $_POST, 0);
  if ($threadId <= 0) {
    throw new InvalidArgumentException('Thiếu thread_id');
  }

  $thread = chat_get_thread_for_admin($threadId);
  if (!$thread) {
    throw new RuntimeException('Không tìm thấy cuộc trò chuyện.');
  }

  chat_assign_admin_if_needed($threadId, (int)$adminId);

  $body = get_str('body', $_POST, '');
  $msgId = chat_insert_message($threadId, 'admin', (int)$adminId, $body);
  $msg = getOne(
    'SELECT id, thread_id, sender_role, sender_id, body, read_by_user_at, read_by_admin_at, created_at
     FROM chat_messages WHERE id = ? LIMIT 1',
    [$msgId]
  );

  echo json_encode([
    'ok' => true,
    'message' => $msg,
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode([
    'ok' => false,
    'error' => $e->getMessage(),
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

