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

<section class="min-h-[calc(100vh-160px)] flex items-center">
  <div class="w-full">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
      <div class="hidden lg:block">
        <div class="bg-white border rounded-xl overflow-hidden">
          <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
            <img
              src="<?= e(app_url('assets/images/logo/login.webp')) ?>"
              class="w-full h-full object-cover"
              alt="Register image"
            >
          </div>
        </div>
      </div>

      <div class="max-w-md mx-auto w-full">
        <form method="POST" class="bg-white border rounded-xl p-6 sm:p-8 space-y-4">
          <div class="flex flex-row items-center justify-between">
            <h1 class="text-2xl font-bold">Đăng kí tài khoản</h1>
          </div>

          <div>
            <label class="block text-sm text-gray-700 mb-1">Họ tên</label>
            <input
              type="text"
              name="name"
              class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Họ tên"
              required
              value="<?= e($_POST['name'] ?? '') ?>"
            >
          </div>

          <div>
            <label class="block text-sm text-gray-700 mb-1">Email</label>
            <input
              type="email"
              name="email"
              class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Địa chỉ email"
              required
              value="<?= e($_POST['email'] ?? '') ?>"
            >
          </div>

          <div>
            <label class="block text-sm text-gray-700 mb-1">Mật khẩu</label>
            <input
              type="password"
              name="password"
              class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Nhập mật khẩu"
              required
            >
          </div>

          <div>
            <label class="block text-sm text-gray-700 mb-1">Nhập lại mật khẩu</label>
            <input
              type="password"
              name="password2"
              class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Nhập lại mật khẩu"
              required
            >
          </div>

          <div class="pt-2">
            <button class="w-full bg-blue-700 text-white rounded py-2 hover:bg-blue-800" type="submit">
              Đăng kí
            </button>

            <p class="text-sm text-gray-600 mt-3">
              Bạn đã có tài khoản?
              <a class="text-red-600 hover:underline font-semibold" href="<?= e(route_url('auth', 'login')) ?>">Đăng nhập ngay</a>
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

