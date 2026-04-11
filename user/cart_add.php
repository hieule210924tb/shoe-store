<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if ($product_id <= 0) {
  set_flash('error', 'Sản phẩm không hợp lệ.');
  redirect('user/index.php');
}

$quantity = max(1, $quantity);

$uid = current_user_id();
if (!$uid) {
  set_flash('error', 'Vui lòng đăng nhập.');
  redirect('auth/login.php');
}

// Kiểm tra tồn kho để không vượt quá stock_qty
$p = getOne('SELECT stock_qty, name FROM products WHERE id = ? LIMIT 1', [$product_id]);
if (!$p) {
  set_flash('error', 'Sản phẩm không tồn tại.');
  redirect('user/index.php');
}

$stock = (int)$p['stock_qty'];
if ($stock <= 0) {
  set_flash('error', 'Sản phẩm hiện đã hết hàng.');
  redirect('user/product_detail.php?id=' . $product_id);
}

// Lấy số lượng hiện tại trong cart
$curRow = getOne('SELECT quantity FROM cart WHERE user_id = ? AND product_id = ? LIMIT 1', [$uid, $product_id]);
$curQty = $curRow ? (int)$curRow['quantity'] : 0;

$toAdd = $quantity;
if ($curQty + $toAdd > $stock) {
  $toAdd = $stock - $curQty;
}

if ($toAdd <= 0) {
  set_flash('error', 'Bạn đã đạt giới hạn tồn kho cho sản phẩm này.');
  redirect('user/cart.php');
}

cart_add_item($uid, $product_id, $toAdd);
set_flash('success', 'Đã thêm vào giỏ hàng.');
redirect('user/cart.php');

