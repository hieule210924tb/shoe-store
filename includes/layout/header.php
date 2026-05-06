<?php
$pageTitle = $pageTitle ?? 'Shoe Store';
$hideHeader = $hideHeader ?? false;
$cartQty = 0;
if (!$hideHeader && is_logged_in()) {
  $uid = current_user_id();
  if ($uid) {
    $cartQty = cart_count_quantity($uid);
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= e(app_url('assets/images/logo/logo.png')) ?>" type="image/png">
  <title><?= e($pageTitle) ?></title>

  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {}
      }
    }
  </script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= e(app_url('assets/css/app.css')) ?>">
</head>
<body class="bg-gray-50 text-gray-900">

<?php if (!$hideHeader): ?>
  <header class="bg-white/90 p-2 backdrop-blur border-b fixed w-full z-50">
  <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">

    <!-- Logo -->
    <a href="<?= e(route_url('user', 'index')) ?>" class="flex items-center gap-2">
      <img width="70" src="<?= e(app_url('assets/images/logo/hiele-preview.png')) ?>" alt="shoe store">
    </a>

    <!-- Menu -->
    <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
      <a href="<?= e(route_url('user', 'index')) ?>" 
         class="flex items-center gap-1 text-gray-700 hover:text-red-600 transition">
        <i class="bi bi-house"></i> Trang chủ
      </a>

      <?php if (is_logged_in()): ?>
        <a href="<?= e(route_url('user', 'cart')) ?>" 
           class="relative flex items-center gap-1 text-gray-700 hover:text-red-600 transition">
          <i class="bi bi-bag text-lg"></i>
          <span>Giỏ hàng</span>

          <?php if ($cartQty > 0): ?>
            <span class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] px-1.5 py-[1px] rounded-full font-semibold">
              <?= $cartQty > 99 ? '99+' : (string)$cartQty ?>
            </span>
          <?php endif; ?>
        </a>

        <a href="<?= e(route_url('user', 'order_history')) ?>" 
           class="flex items-center gap-1 text-gray-700 hover:text-red-600 transition">
          <i class="bi bi-receipt"></i> Đơn đã mua
        </a>
      <?php endif; ?>

      <?php if (is_admin()): ?>
        <a href="<?= e(route_url('admin', 'dashboard')) ?>" 
           class="px-3 py-1.5 rounded bg-red-600 text-white hover:bg-red-700 transition text-xs focus:outline-none focus:ring-2 focus:ring-red-200">
          Admin
        </a>
      <?php endif; ?>
    </nav>

    <!-- Right -->
    <div class="flex items-center gap-3">

      <?php if (is_logged_in()): ?>
        <div class="hidden sm:flex items-center gap-2 text-sm text-gray-600">
          <i class="bi bi-person-circle text-lg"></i>
          <span><?= e($_SESSION['user']['name'] ?? '') ?></span>
        </div>

        <a href="<?= e(route_url('auth', 'logout')) ?>"
           class="text-sm px-3 py-1.5 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 transition focus:outline-none focus:ring-2 focus:ring-red-200">
          Đăng xuất
        </a>

      <?php else: ?>

        <a href="<?= e(route_url('auth', 'login')) ?>"
           class="text-sm px-3 py-1.5 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 transition focus:outline-none focus:ring-2 focus:ring-red-200">
          Đăng nhập
        </a>

        <a href="<?= e(route_url('auth', 'register')) ?>"
           class="text-sm px-4 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition shadow-sm">
          Đăng ký
        </a>

      <?php endif; ?>

    </div>
  </div>
</header>
<?php endif; ?>

<main class="max-w-6xl mx-auto px-4 py-6">
  <?php $flash = get_flash(); if ($flash): ?>
    <div class="mb-4 rounded border p-3
      <?php if ($flash['type'] === 'success'): ?>border-green-300 bg-green-50 text-green-800<?php endif; ?>
      <?php if ($flash['type'] === 'error'): ?>border-red-300 bg-red-50 text-red-800<?php endif; ?>
      <?php if ($flash['type'] === 'info'): ?>border-blue-300 bg-blue-50 text-blue-800<?php endif; ?>
    ">
      <?= e($flash['message'] ?? '') ?>
    </div>
  <?php endif; ?>

