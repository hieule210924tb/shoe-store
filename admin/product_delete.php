<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('admin/products.php');
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
if ($product_id <= 0) {
  set_flash('error', 'ID sản phẩm không hợp lệ.');
  redirect('admin/products.php');
}

try {
  admin_delete_product($product_id);
  set_flash('success', 'Đã xoá sản phẩm.');
} catch (Throwable $e) {
  set_flash('error', 'Không thể xoá sản phẩm (có thể đang có đơn hàng liên quan).');
}

redirect('admin/products.php');

