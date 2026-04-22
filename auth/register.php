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

<section class="min-h-screen flex items-center">
  <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 shadow-xl rounded-2xl overflow-hidden bg-white">

    <!-- LEFT IMAGE -->
    <div class="hidden lg:flex items-center justify-center bg-gray-50 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-gray-200 to-transparent"></div>

      <img 
        src="<?= e(app_url('assets/images/logo/unnamed.png')) ?>"
        alt="Register image"
        class="relative w-[85%] transform rotate-[-10deg] transition-all duration-500 hover:-translate-x-10 hover:rotate-[-12deg] drop-shadow-2xl"
      >
    </div>

    <!-- RIGHT FORM -->
    <div class="flex items-center justify-center p-8 sm:p-12">
      <form method="POST" class="w-full max-w-md space-y-6">

        <!-- Title -->
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Đăng kí</h1>
          <p class="text-gray-500 text-sm mt-2">
            Tạo tài khoản để bắt đầu mua sắm.
          </p>
        </div>

        <!-- Name -->
        <div>
          <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
            Họ và tên
          </label>
          <input
            type="text"
            name="name"
            class="w-full border-b border-gray-300 py-2 focus:outline-none focus:border-red-600 transition"
            placeholder="Nguyễn Văn A"
            required
            value="<?= e($_POST['name'] ?? '') ?>"
          >
        </div>

        <!-- Email -->
        <div>
          <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
            Địa chỉ email
          </label>
          <input
            type="email"
            name="email"
            class="w-full border-b border-gray-300 py-2 focus:outline-none focus:border-red-600 transition"
            placeholder="name@domain.com"
            required
            value="<?= e($_POST['email'] ?? '') ?>"
          >
        </div>

        <!-- Password -->
        <div>
          <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
            Mật khẩu
          </label>
          <input
            type="password"
            name="password"
            class="w-full border-b border-gray-300 py-2 focus:outline-none focus:border-red-600 transition"
            placeholder="••••••••"
            required
          >
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
            Nhập lại mật khẩu
          </label>
          <input
            type="password"
            name="password2"
            class="w-full border-b border-gray-300 py-2 focus:outline-none focus:border-red-600 transition"
            placeholder="••••••••"
            required
          >
        </div>

        <!-- Button -->
        <button 
          type="submit"
          class="w-full bg-red-700 text-white py-3 font-semibold tracking-wide hover:bg-red-800 transition"
        >
          CREATE ACCOUNT
        </button>

        <!-- Login -->
        <p class="text-sm text-gray-600 text-center">
          Bạn đã có tài khoản?
          <a class="text-red-600 font-semibold hover:underline" href="<?= e(route_url('auth', 'login')) ?>">
            Đăng nhập ngay
          </a>
        </p>

      </form>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

