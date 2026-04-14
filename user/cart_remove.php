<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$cart_id = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
if ($cart_id > 0) {
  cart_remove_item(current_user_id(), $cart_id);
  set_flash('success', 'Đã xoá sản phẩm khỏi giỏ hàng.');
}

redirect('user/cart.php');

