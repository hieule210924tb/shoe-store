<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$payload = $_GET;
$requestId = isset($payload['requestId']) ? trim((string)$payload['requestId']) : '';
$transaction = $requestId !== '' ? fetch_momo_transaction_by_request_id($requestId) : null;
$extraData = momo_decode_extra_data(isset($payload['extraData']) ? (string)$payload['extraData'] : '');
$orderId = (int)($extraData['order_id'] ?? ($transaction['order_id'] ?? 0));

try {
  if (!$transaction || $orderId <= 0) {
    throw new RuntimeException('Không tìm thấy giao dịch MoMo tương ứng.');
  }
  if (!momo_verify_return_signature($payload)) {
    throw new RuntimeException('Chữ ký phản hồi MoMo không hợp lệ.');
  }

  $resultCode = (int)($payload['resultCode'] ?? -1);
  if ($resultCode === 0) {
    mark_order_paid($orderId);
    update_momo_transaction_status($orderId, 'paid', $payload, 'return');
    set_flash('success', 'Thanh toán MoMo thành công cho đơn #' . $orderId);
  } else {
    mark_order_cancelled($orderId);
    update_momo_transaction_status($orderId, 'failed', $payload, 'return');
    $message = trim((string)($payload['message'] ?? 'Thanh toán MoMo chưa thành công.'));
    set_flash('error', $message);
  }
} catch (Throwable $e) {
  if ($orderId > 0) {
    update_momo_transaction_status($orderId, 'failed', $payload, 'return');
  }
  set_flash('error', $e->getMessage() ?: 'Không thể xác minh kết quả thanh toán MoMo.');
}

if ($orderId > 0) {
  redirect('user/order_detail.php?id=' . $orderId);
}

redirect('user/order_history.php');
