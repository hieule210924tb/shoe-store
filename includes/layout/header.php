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
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#fef2f2',
              100: '#fee2e2',
              200: '#fecaca',
              300: '#fca5a5',
              400: '#f87171',
              500: '#ef4444',
              600: '#dc2626',
              700: '#b91c1c',
              800: '#991b1b',
              900: '#7f1d1d',
            },
            accent: {
              50: '#fdf4ff',
              100: '#fae8ff',
              200: '#f5d0fe',
              300: '#f0abfc',
              400: '#e879f9',
              500: '#d946ef',
              600: '#c026d3',
              700: '#a21caf',
              800: '#86198f',
              900: '#701a75',
            },
            dark: {
              bg: '#0f172a',
              card: '#1e293b',
              border: '#334155',
              text: '#f1f5f9',
              muted: '#94a3b8',
            }
          },
          animation: {
            'fade-in': 'fadeIn 0.5s ease-out',
            'fade-in-up': 'fadeInUp 0.5s ease-out',
            'fade-in-down': 'fadeInDown 0.5s ease-out',
            'slide-in-right': 'slideInRight 0.5s ease-out',
            'slide-in-left': 'slideInLeft 0.5s ease-out',
            'scale-in': 'scaleIn 0.3s ease-out',
            'bounce-slow': 'bounce 2s infinite',
            'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            'spin-slow': 'spin 3s linear infinite',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            fadeInUp: {
              '0%': { opacity: '0', transform: 'translateY(20px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            },
            fadeInDown: {
              '0%': { opacity: '0', transform: 'translateY(-20px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            },
            slideInRight: {
              '0%': { opacity: '0', transform: 'translateX(100%)' },
              '100%': { opacity: '1', transform: 'translateX(0)' },
            },
            slideInLeft: {
              '0%': { opacity: '0', transform: 'translateX(-100%)' },
              '100%': { opacity: '1', transform: 'translateX(0)' },
            },
            scaleIn: {
              '0%': { opacity: '0', transform: 'scale(0.9)' },
              '100%': { opacity: '1', transform: 'scale(1)' },
            },
          },
          transitionTimingFunction: {
            'smooth': 'cubic-bezier(0.4, 0, 0.2, 1)',
            'bounce': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
          },
          backdropBlur: {
            xs: '2px',
          }
        }
      }
    }
  </script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= e(app_url('assets/css/app.css')) ?>">
  <script src="<?= e(app_url('assets/js/app.js')) ?>" defer></script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-dark-bg dark:text-dark-text transition-colors duration-300">

<?php if (!$hideHeader): ?>
  <!-- Mobile Menu Overlay -->
  <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300"></div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="mobile-menu fixed top-0 left-0 h-full w-64 bg-white dark:bg-dark-card z-50 shadow-xl">
    <div class="p-4 border-b dark:border-dark-border">
      <div class="flex items-center justify-between">
        <a href="<?= e(route_url('user', 'index')) ?>" class="flex items-center gap-2">
          <img width="50" src="<?= e(app_url('assets/images/logo/hiele-preview.png')) ?>" alt="shoe store">
        </a>
        <button id="mobile-menu-toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
          <i class="bi bi-x-lg text-xl"></i>
        </button>
      </div>
    </div>
    <nav class="p-4 space-y-2">
      <a href="<?= e(route_url('user', 'index')) ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition link-underline">
        <i class="bi bi-house"></i> Trang chủ
      </a>
      <?php if (is_logged_in()): ?>
        <a href="<?= e(route_url('user', 'cart')) ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition link-underline">
          <i class="bi bi-bag"></i> Giỏ hàng
          <?php if ($cartQty > 0): ?>
            <span class="ml-auto bg-red-600 text-white text-xs px-2 py-1 rounded-full pulse-badge"><?= $cartQty ?></span>
          <?php endif; ?>
        </a>
        <a href="<?= e(route_url('user', 'order_history')) ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition link-underline">
          <i class="bi bi-receipt"></i> Đơn đã mua
        </a>
      <?php endif; ?>
      <?php if (is_admin()): ?>
        <a href="<?= e(route_url('admin', 'dashboard')) ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
          <i class="bi bi-gear"></i> Admin
        </a>
      <?php endif; ?>
    </nav>
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t dark:border-dark-border">
      <?php if (is_logged_in()): ?>
        <div class="flex items-center gap-3 mb-3">
          <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
            <i class="bi bi-person text-red-600 dark:text-red-400"></i>
          </div>
          <div>
            <div class="font-medium text-sm"><?= e($_SESSION['user']['name'] ?? '') ?></div>
          </div>
        </div>
        <a href="<?= e(route_url('auth', 'logout')) ?>" class="block w-full text-center px-4 py-2 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 transition">
          Đăng xuất
        </a>
      <?php else: ?>
        <a href="<?= e(route_url('auth', 'login')) ?>" class="block w-full text-center px-4 py-2 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 transition mb-2">
          Đăng nhập
        </a>
        <a href="<?= e(route_url('auth', 'register')) ?>" class="block w-full text-center px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
          Đăng ký
        </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Header -->
  <header class="glass fixed w-full z-30 transition-all duration-300">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">

      <!-- Logo & Mobile Toggle -->
      <div class="flex items-center gap-4">
        <button id="mobile-menu-toggle" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
          <i class="bi bi-list text-xl"></i>
        </button>
        <a href="<?= e(route_url('user', 'index')) ?>" class="flex items-center gap-2 group">
          <img width="70" src="<?= e(app_url('assets/images/logo/hiele-preview.png')) ?>" alt="shoe store" class="transition-transform duration-300 group-hover:scale-105">
        </a>
      </div>

      <!-- Desktop Menu -->
      <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
        <a href="<?= e(route_url('user', 'index')) ?>" 
           class="flex items-center gap-1 text-gray-700 dark:text-dark-text hover:text-red-600 transition link-underline">
          <i class="bi bi-house"></i> Trang chủ
        </a>

        <?php if (is_logged_in()): ?>
          <a href="<?= e(route_url('user', 'cart')) ?>" 
             class="relative flex items-center gap-1 text-gray-700 dark:text-dark-text hover:text-red-600 transition link-underline group">
            <i class="bi bi-bag text-lg transition-transform duration-300 group-hover:scale-110"></i>
            <span>Giỏ hàng</span>

            <?php if ($cartQty > 0): ?>
              <span class="absolute -top-2 -right-3 bg-red-600 text-white text-[10px] px-1.5 py-[1px] rounded-full font-semibold pulse-badge shadow-sm">
                <?= $cartQty > 99 ? '99+' : (string)$cartQty ?>
              </span>
            <?php endif; ?>
          </a>

          <a href="<?= e(route_url('user', 'order_history')) ?>" 
             class="flex items-center gap-1 text-gray-700 dark:text-dark-text hover:text-red-600 transition link-underline">
            <i class="bi bi-receipt"></i> Đơn đã mua
          </a>
        <?php endif; ?>

        <?php if (is_admin()): ?>
          <a href="<?= e(route_url('admin', 'dashboard')) ?>" 
             class="px-4 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-all duration-300 hover:shadow-md hover:scale-105 btn-ripple">
            Admin
          </a>
        <?php endif; ?>
      </nav>

      <!-- Right Section -->
      <div class="flex items-center gap-3">

        <!-- Dark Mode Toggle -->
        <button id="dark-mode-toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-300 hover:scale-110" aria-label="Toggle dark mode">
          <i class="bi bi-moon text-xl dark:hidden"></i>
          <i class="bi bi-sun text-xl hidden dark:block text-yellow-400"></i>
        </button>

        <?php if (is_logged_in()): ?>
          <div class="hidden sm:flex items-center gap-2 text-sm text-gray-700 dark:text-dark-text">
            <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
              <i class="bi bi-person text-red-600 dark:text-red-400"></i>
            </div>
            <span class="font-medium"><?= e($_SESSION['user']['name'] ?? '') ?></span>
          </div>

          <a href="<?= e(route_url('auth', 'logout')) ?>"
             class="text-sm px-3 py-1.5 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 transition-all duration-300 hover:shadow-sm btn-ripple">
            Đăng xuất
          </a>

        <?php else: ?>

          <a href="<?= e(route_url('auth', 'login')) ?>"
             class="text-sm px-3 py-1.5 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 transition-all duration-300 hover:shadow-sm btn-ripple">
            Đăng nhập
          </a>

          <a href="<?= e(route_url('auth', 'register')) ?>"
             class="text-sm px-4 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-all duration-300 hover:shadow-md hover:scale-105 btn-ripple">
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

