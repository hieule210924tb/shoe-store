<?php
$pageTitle = $pageTitle ?? 'Shoe Store';
$hideHeader = $hideHeader ?? false;
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>

  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {}
      }
    }
  </script>

  <link rel="stylesheet" href="<?= e(app_url('assets/css/app.css')) ?>">
</head>
<body class="bg-gray-50 text-gray-900">

<?php if (!$hideHeader): ?>
  <header class="bg-white shadow-sm">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
      <a href="<?= e(route_url('user', 'index')) ?>" class="font-bold text-lg text-blue-700 hover:text-blue-800">
        ShoeStore
      </a>

      <nav class="hidden md:flex items-center gap-4 text-sm">
        <a href="<?= e(route_url('user', 'index')) ?>" class="text-gray-700 hover:text-blue-700">Trang chủ</a>
        <?php if (is_logged_in()): ?>
          <a href="<?= e(route_url('user', 'cart')) ?>" class="text-gray-700 hover:text-blue-700">Giỏ hàng</a>
          <a href="<?= e(route_url('user', 'order_history')) ?>" class="text-gray-700 hover:text-blue-700">Đơn đã mua</a>
        <?php endif; ?>
        <?php if (is_admin()): ?>
          <a href="<?= e(route_url('admin', 'dashboard')) ?>" class="text-gray-700 hover:text-blue-700">Admin</a>
        <?php endif; ?>
      </nav>

      <div class="flex items-center gap-3">
        <?php if (is_logged_in()): ?>
          <div class="hidden sm:block text-xs text-gray-600">
            Xin chào, <?= e($_SESSION['user']['name'] ?? '') ?>
          </div>
          <a href="<?= e(route_url('auth', 'logout')) ?>"
             class="text-sm px-3 py-2 rounded border border-gray-200 bg-white hover:bg-gray-100">
            Đăng xuất
          </a>
        <?php else: ?>
          <a href="<?= e(route_url('auth', 'login')) ?>"
             class="text-sm px-3 py-2 rounded border border-gray-200 bg-white hover:bg-gray-100">
            Đăng nhập
          </a>
          <a href="<?= e(route_url('auth', 'register')) ?>"
             class="text-sm px-3 py-2 rounded bg-blue-700 text-white hover:bg-blue-800">
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

