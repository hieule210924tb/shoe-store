<?php

declare(strict_types=1);

if (!defined('_HIEU')) {
  die('Truy cập không hợp lệ');
};

require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Quên mật khẩu';
$hideHeader = true;

if (is_logged_in()) {
  redirect_route('user', 'index');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Rate-limit cơ bản theo session (tránh spam).
  $now = time();
  $last = $_SESSION['pw_reset_last_request_ts'] ?? 0;
  if (is_int($last) && ($now - $last) < 30) {
    set_flash('error', 'Bạn thao tác quá nhanh. Vui lòng thử lại sau ít giây.');
    redirect_route('auth', 'forgot');
  }
  $_SESSION['pw_reset_last_request_ts'] = $now;

  $email = strtolower(get_str('email', $_POST));

  // Trả về thông báo chung để tránh lộ email có tồn tại hay không.
  try {
    $link = create_password_reset_for_email($email);
  } catch (PDOException $e) {
    // Trường hợp phổ biến: chưa import phần bảng `password_resets`
    if (($e->getCode() ?? '') === '42S02') {
      set_flash('error', 'Chưa có bảng password_resets. Bạn hãy import/cập nhật DB bằng file database.sql (hoặc tạo bảng password_resets trong MySQL) rồi thử lại.');
      redirect_route('auth', 'forgot');
    }
    throw $e;
  }
  $msg = 'Nếu email tồn tại trong hệ thống, chúng tôi đã tạo liên kết đặt lại mật khẩu. Vui lòng kiểm tra email của bạn.';

  // Nếu chưa bật gửi mail, hiển thị link để test local.
  if ((!defined('MAIL_ENABLED') || MAIL_ENABLED !== true) && is_string($link) && $link !== '') {
    $msg .= ' Link reset (demo): ' . $link;
  }

  set_flash('info', $msg);
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
        alt="Forgot password image"
        class="relative w-[85%] transform rotate-[-10deg] transition-all duration-500 hover:-translate-x-10 hover:rotate-[-12deg] drop-shadow-2xl"
      >
    </div>

    <!-- RIGHT FORM -->
    <div class="flex items-center justify-center p-8 sm:p-12">
      <form method="POST" class="w-full max-w-md space-y-6">

        <!-- Title -->
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Quên mật khẩu</h1>
          <p class="text-gray-500 text-sm mt-2">
            Nhập email của bạn để nhận liên kết đặt lại mật khẩu.
          </p>
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

        <!-- Button -->
        <button 
          type="submit"
          class="w-full bg-red-700 text-white py-3 font-semibold tracking-wide hover:bg-red-800 transition"
        >
          Gửi liên kết đặt lại mật khẩu
        </button>

        <!-- Back -->
        <div class="text-center text-sm text-gray-600">
          Quay lại 
          <a class="text-red-600 font-semibold hover:underline" href="<?= e(route_url('auth', 'login')) ?>">
            Đăng nhập
          </a>
        </div>

      </form>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

