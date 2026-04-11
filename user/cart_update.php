<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if ($product_id <= 0) {
  set_flash('error', 'Sản phẩm không hợp lệ.');
  redirect('user/cart.php');
}

$quantity = (int)$quantity;
if ($quantity < 0) {
  $quantity = 0;
}
$uid = current_user_id();

// Kiểm tra tồn kho
$p = getOne('SELECT stock_qty FROM products WHERE id = ? LIMIT 1', [$product_id]);
if (!$p) {
  set_flash('error', 'Sản phẩm không tồn tại.');
  redirect('user/cart.php');
}
$stock = (int)$p['stock_qty'];

if ($quantity > $stock) {
  set_flash('error', 'Số lượng vượt quá tồn kho. Mình sẽ tự giảm về tối đa còn lại.');
  $quantity = $stock;
}

cart_update_quantity($uid, $product_id, $quantity);
redirect('user/cart.php');

