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

  $thread = chat_get_or_create_thread_for_user((int)$uid);
  $threadId = (int)$thread['id'];

  chat_mark_read_for_user($threadId);

  echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode([
    'ok' => false,
    'error' => $e->getMessage(),
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

