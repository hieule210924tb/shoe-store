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

<section class="min-h-[calc(100vh-160px)] flex items-center">
  <div class="w-full">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
      <div class="hidden lg:block">
        <div class="bg-white border rounded-xl overflow-hidden">
          <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
            <img
              src="<?= e(app_url('assets/images/logo/login.webp')) ?>"
              class="w-full h-full object-cover"
              alt="Forgot password image"
            >
          </div>
        </div>
      </div>

      <div class="max-w-md mx-auto w-full">
        <form method="POST" class="bg-white border rounded-xl p-6 sm:p-8 space-y-4">
          <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Quên mật khẩu</h1>
          </div>
          <p class="text-gray-600 text-sm">Nhập email của bạn để nhận liên kết đặt lại mật khẩu.</p>

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

          <div class="pt-2">
            <button class="w-full bg-blue-700 text-white rounded py-2 hover:bg-blue-800" type="submit">
              Gửi
            </button>
            <div class="text-center text-sm mt-3 text-gray-600">
              Quay lại <a class="text-blue-700 hover:underline" href="<?= e(route_url('auth', 'login')) ?>">Đăng nhập</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

