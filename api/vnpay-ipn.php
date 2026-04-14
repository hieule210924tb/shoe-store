<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$payload = $_GET;
if ($payload === []) {
  $rawInput = file_get_contents('php://input') ?: '';
  parse_str($rawInput, $tmp);
  if (is_array($tmp)) {
    $payload = $tmp;
  }
}

$txnRef = isset($payload['vnp_TxnRef']) ? trim((string)$payload['vnp_TxnRef']) : '';
$transaction = $txnRef !== '' ? fetch_vnpay_transaction_by_txn_ref($txnRef) : null;
$orderId = (int)($transaction['order_id'] ?? 0);

try {
  if (!$transaction || $orderId <= 0) {
    throw new RuntimeException('Transaction not found');
  }
  if (!vnpay_verify_signature($payload)) {
    throw new RuntimeException('Invalid signature');
  }

  $responseCode = (string)($payload['vnp_ResponseCode'] ?? '');
  $transactionStatus = (string)($payload['vnp_TransactionStatus'] ?? '');

  if ($responseCode === '00' && ($transactionStatus === '' || $transactionStatus === '00')) {
    mark_order_paid($orderId);
    update_vnpay_transaction_status($orderId, 'paid', $payload, 'ipn');
  } else {
    mark_order_cancelled($orderId);
    update_vnpay_transaction_status($orderId, 'failed', $payload, 'ipn');
  }

  echo json_encode(['RspCode' => '00', 'Message' => 'Confirm Success'], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  if ($orderId > 0) {
    update_vnpay_transaction_status($orderId, 'failed', $payload, 'ipn');
  }
  http_response_code(400);
  echo json_encode(['RspCode' => '99', 'Message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
