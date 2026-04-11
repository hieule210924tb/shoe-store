<?php

declare(strict_types=1);
if (!defined('_HIEU')) {
  die('Truy cập không hợp lệ');
};
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Đặt lại mật khẩu';

if (is_logged_in()) {
  redirect_route('user', 'index');
}

$token = get_str('token', $_GET, '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = get_str('token', $_POST, '');
  $password = get_str('password', $_POST);
  $password2 = get_str('password2', $_POST);

  if ($token === '') {
    set_flash('error', 'Thiếu token đặt lại mật khẩu.');
    redirect_route('auth', 'forgot');
  }

  if ($password === '' || strlen($password) < 6) {
    set_flash('error', 'Mật khẩu cần ít nhất 6 ký tự.');
    redirect_route('auth', 'reset', ['token' => $token]);
  }

  if ($password !== $password2) {
    set_flash('error', 'Mật khẩu nhập lại không khớp.');
    redirect_route('auth', 'reset', ['token' => $token]);
  }

  $ok = consume_password_reset_token($token, $password);
  if (!$ok) {
    set_flash('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
    redirect_route('auth', 'forgot');
  }

  set_flash('success', 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập bằng mật khẩu mới.');
  redirect_route('auth', 'login');
}

$uid = ($token !== '') ? find_user_id_by_password_reset_token($token) : null;

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="max-w-md mx-auto">
  <h1 class="text-2xl font-bold mb-2">Đặt lại mật khẩu</h1>

  <?php if (!$uid): ?>
    <div class="bg-white border rounded p-4">
      <p class="text-gray-700 mb-3">Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.</p>
      <a class="text-blue-700 hover:underline" href="<?= e(app_url('auth/forgot_password.php')) ?>">Tạo liên kết mới</a>
    </div>
  <?php else: ?>
    <form method="POST" class="bg-white border rounded p-4 space-y-3">
      <input type="hidden" name="token" value="<?= e($token) ?>">

      <div>
        <label class="block text-sm text-gray-700 mb-1">Mật khẩu mới</label>
        <input name="password" type="password" class="w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Nhập lại mật khẩu mới</label>
        <input name="password2" type="password" class="w-full border rounded px-3 py-2" required>
      </div>

      <button class="w-full bg-blue-700 text-white rounded py-2 hover:bg-blue-800" type="submit">
        Cập nhật mật khẩu
      </button>
    </form>
  <?php endif; ?>

  <div class="text-center text-sm mt-4 text-gray-600">
    Quay lại <a class="text-blue-700 hover:underline" href="<?= e(app_url('auth/login.php')) ?>">Đăng nhập</a>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

