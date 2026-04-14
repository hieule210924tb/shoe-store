<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$cart_id = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$shoe_size = isset($_POST['shoe_size']) ? (int)$_POST['shoe_size'] : 40;

if ($cart_id <= 0) {
  set_flash('error', 'Sản phẩm không hợp lệ.');
  redirect('user/cart.php');
}

$quantity = (int)$quantity;
if ($quantity < 0) {
  $quantity = 0;
}
$uid = current_user_id();
if (!is_valid_shoe_size($shoe_size)) {
  set_flash('error', 'Size giày không hợp lệ.');
  redirect('user/cart.php');
}

// Kiểm tra cart item + tồn kho
$item = getOne(
  'SELECT c.product_id, p.stock_qty
   FROM cart c
   JOIN products p ON p.id = c.product_id
   WHERE c.id = ? AND c.user_id = ?
   LIMIT 1',
  [$cart_id, $uid]
);
if (!$item) {
  set_flash('error', 'Không tìm thấy sản phẩm trong giỏ.');
  redirect('user/cart.php');
}

$stock = (int)$item['stock_qty'];

if ($quantity > $stock) {
  set_flash('error', 'Số lượng vượt quá tồn kho. Mình sẽ tự giảm về tối đa còn lại.');
  $quantity = $stock;
}

cart_update_item($uid, $cart_id, $shoe_size, $quantity);
redirect('user/cart.php');

