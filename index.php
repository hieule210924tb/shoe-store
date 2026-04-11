<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// Router: ?module=...&action=...
$module = get_str('module', $_GET, '');
$action = get_str('action', $_GET, '');

if ($module === '' && $action === '') {
  // Trang mặc định
  if (is_logged_in()) {
    if (is_admin()) {
      redirect_route('admin', 'dashboard');
    }
    redirect_route('user', 'index');
  }
  redirect_route('user', 'index');
}

$routes = [
  'auth' => [
    'login' => __DIR__ . '/auth/login.php',
    'register' => __DIR__ . '/auth/register.php',
    'forgot' => __DIR__ . '/auth/forgot_password.php',
    'reset' => __DIR__ . '/auth/reset_password.php',
    'logout' => __DIR__ . '/auth/logout.php',
  ],
  'user' => [
    'index' => __DIR__ . '/user/index.php',
    'cart' => __DIR__ . '/user/cart.php',
    'cart_add' => __DIR__ . '/user/cart_add.php',
    'cart_update' => __DIR__ . '/user/cart_update.php',
    'cart_remove' => __DIR__ . '/user/cart_remove.php',
    'product_detail' => __DIR__ . '/user/product_detail.php',
    'checkout' => __DIR__ . '/user/checkout.php',
    'checkout_confirm' => __DIR__ . '/user/checkout_confirm.php',
    'order_history' => __DIR__ . '/user/order_history.php',
    'order_detail' => __DIR__ . '/user/order_detail.php',
  ],
  'admin' => [
    'dashboard' => __DIR__ . '/admin/dashboard.php',
    'accounts' => __DIR__ . '/admin/accounts.php',
    'products' => __DIR__ . '/admin/products.php',
    'product_add' => __DIR__ . '/admin/product_add.php',
    'product_edit' => __DIR__ . '/admin/product_edit.php',
    'product_delete' => __DIR__ . '/admin/product_delete.php',
    'categories' => __DIR__ . '/admin/categories.php',
    'orders' => __DIR__ . '/admin/orders.php',
    'order_detail' => __DIR__ . '/admin/order_detail.php',
  ],
];

if (!isset($routes[$module][$action])) {
  http_response_code(404);
  $pageTitle = 'Không tìm thấy';
  require_once __DIR__ . '/includes/layout/header.php';
  ?>
  <div class="bg-white border rounded p-6">
    <h1 class="text-xl font-bold mb-2">404 - Không tìm thấy trang</h1>
    <p class="text-gray-600">Đường dẫn không hợp lệ.</p>
  </div>
  <?php
  require_once __DIR__ . '/includes/layout/footer.php';
  exit;
}

require $routes[$module][$action];

