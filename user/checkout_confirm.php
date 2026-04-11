<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$pageTitle = 'Xác nhận thanh toán';

$uid = current_user_id();

try {
  $orderId = cart_create_order_from_cart($uid);
  set_flash('success', 'Thanh toán thành công! Mã đơn: #' . $orderId);
  redirect('user/order_detail.php?id=' . $orderId);
} catch (Throwable $e) {
  set_flash('error', $e->getMessage() ?: 'Có lỗi khi thanh toán.');
  redirect('user/checkout.php');
}

