<?php
declare(strict_types=1);
if (!defined('_HIEU')) {
  die('Truy cập không hợp lệ');
};


require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Đăng nhập';
$hideHeader = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = strtolower(get_str('email', $_POST));
  $password = get_str('password', $_POST);

  if ($email === '' || $password === '') {
    set_flash('error', 'Vui lòng nhập email và mật khẩu.');
    redirect_route('auth', 'login');
  }

  $user = getOne('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1', [$email]);

  if (!$user || !password_verify($password, $user['password_hash'])) {
    set_flash('error', 'Email hoặc mật khẩu không đúng.');
    redirect_route('auth', 'login');
  }

  // Tạo session mới sau khi đăng nhập để tránh dính session cũ
  if (session_status() === PHP_SESSION_ACTIVE) {
    session_regenerate_id(true);
  }

  $_SESSION['user'] = [
    'id' => (int)$user['id'],
    'name' => $user['name'],
    'role' => $user['role'],
  ];

  if ($user['role'] === 'admin') {
    redirect_route('admin', 'dashboard');
  }
  redirect_route('user', 'index');
}

require_once __DIR__ . '/../includes/layout/header.php';
?>

<section class="min-h-[calc(100vh-160px)] flex items-center">
  <div class="w-full">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
      <div class="hidden lg:block">
        <div class="bg-white border rounded-xl overflow-hidden">
          <div class="aspect-[4/3] bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
            <div class="col-md-9 col-lg-6 col-xl-5">
            <img src="<?= e(app_url('assets/images/logo/login.webp')) ?>" class="img-fluid"
                    alt="Login image">
            </div>
          </div>
        </div>
      </div>

      <div class="max-w-md mx-auto w-full">
        <form method="POST" class="bg-white border rounded-xl p-6 sm:p-8 space-y-4">
          <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Đăng nhập hệ thống</h1>
          </div>

          <div>
            <label class="block text-sm text-gray-700 mb-1">Email</label>
            <input
              name="email"
              type="email"
              class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Địa chỉ email"
              required
              value="<?= e($_POST['email'] ?? '') ?>"
            >
          </div>

          <div>
            <label class="block text-sm text-gray-700 mb-1">Mật khẩu</label>
            <input
              name="password"
              type="password"
              class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Nhập mật khẩu"
              required
            >
          </div>

          <div class="flex justify-between items-center text-sm">
            <a class="text-gray-700 hover:text-blue-700 hover:underline" href="<?= e(route_url('auth', 'forgot')) ?>">
              Quên mật khẩu?
            </a>
          </div>

          <div class="pt-2">
            <button class="w-full bg-blue-700 text-white rounded py-2 hover:bg-blue-800" type="submit">
              Đăng nhập
            </button>
            <p class="text-sm text-gray-600 mt-3">
              Bạn chưa có tài khoản?
              <a class="text-red-600 hover:underline font-semibold" href="<?= e(route_url('auth', 'register')) ?>">Đăng kí ngay</a>
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

