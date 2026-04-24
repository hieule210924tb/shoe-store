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

  $threadId = get_int('thread_id', $_POST, 0);
  if ($threadId <= 0) {
    throw new InvalidArgumentException('Thiếu thread_id');
  }

  $thread = chat_get_thread_for_admin($threadId);
  if (!$thread) {
    throw new RuntimeException('Không tìm thấy cuộc trò chuyện.');
  }

  chat_mark_read_for_admin($threadId);

  echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode([
    'ok' => false,
    'error' => $e->getMessage(),
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

