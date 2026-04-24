<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

header('Content-Type: application/json; charset=utf-8');

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new RuntimeException('Method not allowed');
  }

  $uid = current_user_id();
  if (!$uid) {
    throw new RuntimeException('Chưa đăng nhập.');
  }

  $body = get_str('body', $_POST, '');

  $thread = chat_get_or_create_thread_for_user((int)$uid);
  $threadId = (int)$thread['id'];

  $msgId = chat_insert_message($threadId, 'user', (int)$uid, $body);
  $msg = getOne(
    'SELECT id, thread_id, sender_role, sender_id, body, read_by_user_at, read_by_admin_at, created_at
     FROM chat_messages WHERE id = ? LIMIT 1',
    [$msgId]
  );

  echo json_encode([
    'ok' => true,
    'thread_id' => $threadId,
    'message' => $msg,
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode([
    'ok' => false,
    'error' => $e->getMessage(),
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

