<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('admin/orders.php');
}

$orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$nextStatus = trim((string)($_POST['next_status'] ?? ''));

if ($orderId <= 0 || !in_array($nextStatus, ['paid', 'cancelled'], true)) {
  set_flash('error', 'Dữ liệu cập nhật trạng thái không hợp lệ.');
  redirect('admin/orders.php');
}

try {
  admin_update_cod_pending_order_status($orderId, $nextStatus);
  if ($nextStatus === 'paid') {
    set_flash('success', 'Đã cập nhật đơn COD sang trạng thái paid.');
  } else {
    set_flash('success', 'Đã hủy đơn COD và hoàn lại tồn kho.');
  }
} catch (Throwable $e) {
  set_flash('error', $e->getMessage() ?: 'Không thể cập nhật trạng thái đơn hàng.');
}

redirect('admin/order_detail.php?id=' . $orderId);
