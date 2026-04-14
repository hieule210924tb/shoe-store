<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$rawInput = file_get_contents('php://input') ?: '';
$payload = json_decode($rawInput, true);
if (!is_array($payload) || $payload === []) {
  if (!empty($_POST)) {
    $payload = $_POST;
  } else {
    $tmp = [];
    parse_str($rawInput, $tmp);
    if (is_array($tmp) && $tmp !== []) {
      $payload = $tmp;
    }
  }
}
if (!is_array($payload) || $payload === []) {
  http_response_code(400);
  echo json_encode(['resultCode' => 1, 'message' => 'Invalid payload'], JSON_UNESCAPED_UNICODE);
  exit;
}

$requestId = isset($payload['requestId']) ? trim((string)$payload['requestId']) : '';
$transaction = $requestId !== '' ? fetch_momo_transaction_by_request_id($requestId) : null;
$extraData = momo_decode_extra_data(isset($payload['extraData']) ? (string)$payload['extraData'] : '');
$orderId = (int)($extraData['order_id'] ?? ($transaction['order_id'] ?? 0));

try {
  if (!$transaction || $orderId <= 0) {
    throw new RuntimeException('Transaction not found');
  }
  if (!momo_verify_return_signature($payload)) {
    throw new RuntimeException('Invalid signature');
  }

  $resultCode = (int)($payload['resultCode'] ?? -1);
  if ($resultCode === 0) {
    mark_order_paid($orderId);
    update_momo_transaction_status($orderId, 'paid', $payload, 'ipn');
  } else {
    mark_order_cancelled($orderId);
    update_momo_transaction_status($orderId, 'failed', $payload, 'ipn');
  }

  echo json_encode(['resultCode' => 0, 'message' => 'Success'], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  if ($orderId > 0) {
    update_momo_transaction_status($orderId, 'failed', $payload, 'ipn');
  }
  http_response_code(400);
  echo json_encode(['resultCode' => 1, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
