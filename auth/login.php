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

<section class=" flex items-center">
  <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 shadow-xl rounded-2xl overflow-hidden bg-white">

    <!-- LEFT IMAGE -->
    <div class="hidden lg:flex items-center justify-center bg-gray-50 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-gray-200 to-transparent"></div>

      <img 
        src="<?= e(app_url('assets/images/logo/unnamed.png')) ?>"
        alt="Login image"
        class="relative w-[85%] transform rotate-[-10deg] transition-all duration-500 hover:-translate-x-10 hover:rotate-[-12deg] drop-shadow-2xl"
      >
    </div>

    <!-- RIGHT FORM -->
    <div class="flex items-center justify-center p-8 sm:p-12">
      <form method="POST" class="w-full max-w-md space-y-6">

        <div>
          <h1 class="text-3xl font-bold text-gray-900">Đăng nhập</h1>
        </div>

        <!-- Email -->
        <div>
          <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Địa chỉ Email</label>
          <input
            name="email"
            type="email"
            class="w-full border-b border-gray-300 py-2 focus:outline-none focus:border-red-600 transition"
            placeholder="name@email.com"
            required
            value="<?= e($_POST['email'] ?? '') ?>"
          >
        </div>

        <!-- Password -->
        <div>
          <div class="flex justify-between text-xs font-semibold text-gray-500 uppercase">
            <label>Mật khẩu</label>
            <a href="<?= e(route_url('auth', 'forgot')) ?>" class="text-red-600 hover:underline">
              Quên mật khẩu?
            </a>
          </div>

          <input
            name="password"
            type="password"
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
          LOG IN
        </button>

        <!-- Register -->
        <p class="text-sm text-gray-600 text-center">
          Bạn chưa có tài khoản?
          <a class="text-red-600 font-semibold hover:underline" href="<?= e(route_url('auth', 'register')) ?>">
            Đăng ký
          </a>
        </p>

      </form>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

