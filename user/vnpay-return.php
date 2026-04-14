<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$payload = $_GET;
$txnRef = isset($payload['vnp_TxnRef']) ? trim((string)$payload['vnp_TxnRef']) : '';
$transaction = $txnRef !== '' ? fetch_vnpay_transaction_by_txn_ref($txnRef) : null;
$orderId = (int)($transaction['order_id'] ?? 0);

try {
  if (!$transaction || $orderId <= 0) {
    throw new RuntimeException('Không tìm thấy giao dịch VNPay tương ứng.');
  }
  if (!vnpay_verify_signature($payload)) {
    throw new RuntimeException('Chữ ký phản hồi VNPay không hợp lệ.');
  }

  $responseCode = (string)($payload['vnp_ResponseCode'] ?? '');
  $transactionStatus = (string)($payload['vnp_TransactionStatus'] ?? '');

  if ($responseCode === '00' && ($transactionStatus === '' || $transactionStatus === '00')) {
    mark_order_paid($orderId);
    update_vnpay_transaction_status($orderId, 'paid', $payload, 'return');
    set_flash('success', 'Thanh toán VNPay thành công cho đơn #' . $orderId);
  } else {
    mark_order_cancelled($orderId);
    update_vnpay_transaction_status($orderId, 'failed', $payload, 'return');
    set_flash('error', 'Thanh toán VNPay chưa thành công.');
  }
} catch (Throwable $e) {
  if ($orderId > 0) {
    update_vnpay_transaction_status($orderId, 'failed', $payload, 'return');
  }
  set_flash('error', $e->getMessage() ?: 'Không thể xác minh kết quả thanh toán VNPay.');
}

if ($orderId > 0) {
  redirect('user/order_detail.php?id=' . $orderId);
}

redirect('user/order_history.php');
