<?php


declare(strict_types=1);
// Ví dụ: localhost trong XAMPP
define('DB_HOST', 'localhost');
define('DB_NAME', 'shoe-store');
define('DB_USER', 'root');
define('DB_PASS', '');

// ===== SMTP (PHPMailer) =====
// Để bật gửi mail, set MAIL_ENABLED = true và điền thông tin SMTP.
define('MAIL_ENABLED', true);
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '');
define('MAIL_PASSWORD', ''); // Gmail: dùng App Password (không dùng mật khẩu đăng nhập)
define('MAIL_ENCRYPTION', 'tls'); // 'tls' | 'ssl' | ''
define('MAIL_FROM_EMAIL', 'no-reply@example.com');
define('MAIL_FROM_NAME', 'ShoeStore');

// BASE_URL: phần đường dẫn URL tới thư mục project.
// Mình tự suy ra từ REQUEST_URI dựa trên tên thư mục project để tránh bạn phải chỉnh thủ công.
$projectName = basename(dirname(__DIR__)); // ví dụ: 'shoe_store'
$reqUri = $_SERVER['REQUEST_URI'] ?? '';
$baseUrl = '';
$needle = '/' . $projectName;
if (is_string($reqUri) && $reqUri !== '') {
  $pos = strrpos($reqUri, $needle);
  if ($pos !== false) {
    $baseUrl = substr($reqUri, 0, $pos + strlen($needle));
  }
}
define('BASE_URL', $baseUrl);

