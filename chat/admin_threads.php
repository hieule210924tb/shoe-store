<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

header('Content-Type: application/json; charset=utf-8');

try {
  $limit = get_int('limit', $_GET, 100);
  $threads = chat_list_threads_for_admin($limit);

  echo json_encode([
    'ok' => true,
    'threads' => $threads,
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode([
    'ok' => false,
    'error' => $e->getMessage(),
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

