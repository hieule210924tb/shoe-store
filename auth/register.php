<?php

declare(strict_types=1);
if (!defined('_HIEU')) {
  die('Truy cập không hợp lệ');
};
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Đăng ký';
$hideHeader = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = get_str('name', $_POST);
  $email = strtolower(get_str('email', $_POST));
  $password = get_str('password', $_POST);
  $password2 = get_str('password2', $_POST);

  if ($name === '' || strlen($name) < 2) {
    set_flash('error', 'Vui lòng nhập tên hợp lệ.');
    redirect_route('auth', 'register');
  }

  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Vui lòng nhập email hợp lệ.');
    redirect_route('auth', 'register');
  }

  if ($password === '' || strlen($password) < 6) {
    set_flash('error', 'Mật khẩu cần ít nhất 6 ký tự.');
    redirect_route('auth', 'register');
  }

  if ($password !== $password2) {
    set_flash('error', 'Mật khẩu nhập lại không khớp.');
    redirect_route('auth', 'register');
  }

  // Kiểm tra email đã tồn tại
  $exists = getOne('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
  if ($exists) {
    set_flash('error', 'Email đã được sử dụng.');
    redirect_route('auth', 'register');
  }

  // Mặc định người dùng đăng ký là role "user".
  // Admin sẽ được tạo qua `seed_data.sql` hoặc bạn tạo bằng SQL thủ công.
  $role = 'user';

  $hash = password_hash($password, PASSWORD_DEFAULT);
  insertRow('users', [
    'name' => $name,
    'email' => $email,
    'password_hash' => $hash,
    'role' => $role,
  ]);

  $userId = (int)db()->lastInsertId();
  $_SESSION['user'] = [
    'id' => $userId,
    'name' => $name,
    'role' => $role,
  ];

  set_flash('success', 'Đăng ký thành công!');
  if ($role === 'admin') {
    redirect_route('admin', 'dashboard');
  }
  redirect_route('auth', 'login');
}

require_once __DIR__ . '/../includes/layout/header.php';
?>

<section class="min-h-screen flex items-center justify-center p-4 animate-on-scroll">
  <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 shadow-2xl rounded-3xl overflow-hidden bg-white dark:bg-dark-card">

    <!-- LEFT IMAGE -->
    <div class="hidden lg:flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-transparent"></div>
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="w-64 h-64 bg-red-500/20 rounded-full blur-3xl animate-pulse"></div>
      </div>

      <img 
        src="<?= e(app_url('assets/images/logo/unnamed.png')) ?>"
        alt="Register image"
        class="relative w-[85%] transform rotate-[-10deg] transition-all duration-700 hover:-translate-x-10 hover:rotate-[-12deg] hover:scale-105 drop-shadow-2xl"
      >
      
      <div class="absolute bottom-8 left-8 right-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-dark-text mb-2">Tạo tài khoản mới</h2>
        <p class="text-gray-600 dark:text-dark-muted">Tham gia cùng chúng tôi để trải nghiệm mua sắm tốt nhất</p>
      </div>
    </div>

    <!-- RIGHT FORM -->
    <div class="flex items-center justify-center p-8 sm:p-12 relative">
      <div class="absolute top-4 right-4">
        <button id="dark-mode-toggle" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-300 hover:scale-110" aria-label="Toggle dark mode">
          <i class="bi bi-moon text-xl dark:hidden text-gray-600"></i>
          <i class="bi bi-sun text-xl hidden dark:block text-yellow-400"></i>
        </button>
      </div>
      
      <form method="POST" class="w-full max-w-md space-y-5">

        <!-- Title -->
        <div class="animate-fade-in-up" style="animation-delay: 100ms; animation-fill-mode: forwards; opacity: 0;">
          <h1 class="text-4xl font-bold text-gray-900 dark:text-dark-text mb-2">Đăng ký</h1>
          <p class="text-gray-600 dark:text-dark-muted text-sm">
            Tạo tài khoản để bắt đầu mua sắm.
          </p>
        </div>

        <!-- Name -->
        <div class="animate-fade-in-up" style="animation-delay: 200ms; animation-fill-mode: forwards; opacity: 0;">
          <label class="text-sm font-semibold text-gray-700 dark:text-dark-text mb-2 block">
            Họ và tên
          </label>
          <div class="relative">
            <i class="bi bi-person absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              type="text"
              name="name"
              class="w-full border-2 border-gray-200 dark:border-dark-border rounded-xl py-3 pl-12 pr-4 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300"
              placeholder="Nguyễn Văn A"
              required
              value="<?= e($_POST['name'] ?? '') ?>"
            >
          </div>
        </div>

        <!-- Email -->
        <div class="animate-fade-in-up" style="animation-delay: 300ms; animation-fill-mode: forwards; opacity: 0;">
          <label class="text-sm font-semibold text-gray-700 dark:text-dark-text mb-2 block">
            Địa chỉ email
          </label>
          <div class="relative">
            <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              type="email"
              name="email"
              class="w-full border-2 border-gray-200 dark:border-dark-border rounded-xl py-3 pl-12 pr-4 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300"
              placeholder="name@domain.com"
              required
              value="<?= e($_POST['email'] ?? '') ?>"
            >
          </div>
        </div>

        <!-- Password -->
        <div class="animate-fade-in-up" style="animation-delay: 400ms; animation-fill-mode: forwards; opacity: 0;">
          <label class="text-sm font-semibold text-gray-700 dark:text-dark-text mb-2 block">
            Mật khẩu
          </label>
          <div class="relative">
            <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              type="password"
              name="password"
              class="w-full border-2 border-gray-200 dark:border-dark-border rounded-xl py-3 pl-12 pr-12 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300"
              placeholder="••••••••"
              required
            >
            <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          <p class="text-xs text-gray-500 dark:text-dark-muted mt-1">Tối thiểu 6 ký tự</p>
        </div>

        <!-- Confirm Password -->
        <div class="animate-fade-in-up" style="animation-delay: 500ms; animation-fill-mode: forwards; opacity: 0;">
          <label class="text-sm font-semibold text-gray-700 dark:text-dark-text mb-2 block">
            Nhập lại mật khẩu
          </label>
          <div class="relative">
            <i class="bi bi-lock-fill absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              type="password"
              name="password2"
              class="w-full border-2 border-gray-200 dark:border-dark-border rounded-xl py-3 pl-12 pr-12 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300"
              placeholder="••••••••"
              required
            >
            <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <!-- Button -->
        <div class="animate-fade-in-up" style="animation-delay: 600ms; animation-fill-mode: forwards; opacity: 0;">
          <button 
            type="submit"
            class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-4 rounded-xl font-semibold tracking-wide hover:from-red-700 hover:to-red-800 transition-all duration-300 hover:scale-105 hover:shadow-lg btn-ripple"
          >
            <i class="bi bi-person-plus mr-2"></i> Đăng ký
          </button>
        </div>

        <!-- Login -->
        <div class="text-center animate-fade-in-up" style="animation-delay: 700ms; animation-fill-mode: forwards; opacity: 0;">
          <p class="text-sm text-gray-600 dark:text-dark-muted">
            Bạn đã có tài khoản?
            <a class="text-red-600 dark:text-red-400 font-semibold hover:text-red-700 dark:hover:text-red-300 transition link-underline" href="<?= e(route_url('auth', 'login')) ?>">
              Đăng nhập ngay
            </a>
          </p>
        </div>

        <!-- Social Register -->
        <div class="animate-fade-in-up" style="animation-delay: 800ms; animation-fill-mode: forwards; opacity: 0;">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-200 dark:border-dark-border"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white dark:bg-dark-card text-gray-500 dark:text-dark-muted">Hoặc đăng ký với</span>
            </div>
          </div>
          
          <div class="mt-4 flex gap-3">
            <button type="button" class="flex-1 flex items-center justify-center gap-2 py-3 border-2 border-gray-200 dark:border-dark-border rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 hover:scale-105">
              <i class="bi bi-google text-red-500"></i>
              <span class="text-sm font-medium text-gray-700 dark:text-dark-text">Google</span>
            </button>
            <button type="button" class="flex-1 flex items-center justify-center gap-2 py-3 border-2 border-gray-200 dark:border-dark-border rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 hover:scale-105">
              <i class="bi bi-facebook text-blue-600"></i>
              <span class="text-sm font-medium text-gray-700 dark:text-dark-text">Facebook</span>
            </button>
          </div>
        </div>

      </form>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

