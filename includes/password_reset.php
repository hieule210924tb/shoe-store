<?php

declare(strict_types=1);

function mailer_autoload_if_needed(): void
{
  // Dự án của bạn đã manual PHPMailer trong `config/functions.php`.
  // bootstrap đã require file đó, nên thường không cần làm gì ở đây.
  return;
}

function send_password_reset_email(string $toEmail, string $resetLink): bool
{
  if (!defined('MAIL_ENABLED') || MAIL_ENABLED !== true) {
    return false;
  }

  $toEmail = strtolower(trim($toEmail));
  if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  $safeLink = e($resetLink);
  $subject = 'Đặt lại mật khẩu ShoeStore';
  $body = <<<HTML
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0;padding:24px 12px;background:#f3f4f6;font-family:Segoe UI,Roboto,Helvetica,Arial,sans-serif;">
  <tr>
    <td align="center">
      <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 10px 25px rgba(15,23,42,0.06);">
        <tr>
          <td style="padding:22px 26px 8px 26px;background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#ffffff;">
            <div style="font-size:18px;font-weight:700;letter-spacing:0.2px;">ShoeStore</div>
            <div style="font-size:13px;opacity:0.95;margin-top:4px;">Đặt lại mật khẩu</div>
          </td>
        </tr>
        <tr>
          <td style="padding:24px 26px 8px 26px;color:#111827;font-size:15px;line-height:1.6;">
            <p style="margin:0 0 14px 0;">Xin chào,</p>
            <p style="margin:0 0 16px 0;">Bạn (hoặc ai đó) vừa yêu cầu <strong>đặt lại mật khẩu</strong> cho tài khoản ShoeStore. Nhấn nút bên dưới để tiếp tục.</p>
            <p style="margin:0 0 20px 0;font-size:13px;color:#6b7280;">Liên kết có hiệu lực trong <strong>30 phút</strong>. Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email.</p>
            <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 22px 0;">
              <tr>
                <td align="center" bgcolor="#2563eb" style="border-radius:8px;">
                  <a href="{$safeLink}" style="display:inline-block;padding:12px 28px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:8px;">Đặt lại mật khẩu</a>
                </td>
              </tr>
            </table>
            <p style="margin:0 0 8px 0;font-size:12px;color:#6b7280;">Nếu nút không hoạt động, copy và dán đường dẫn sau vào trình duyệt:</p>
            <p style="margin:0 0 0 0;font-size:12px;word-break:break-all;color:#2563eb;"><a href="{$safeLink}" style="color:#2563eb;text-decoration:underline;">{$safeLink}</a></p>
          </td>
        </tr>
        <tr>
          <td style="padding:0 26px 22px 26px;color:#9ca3af;font-size:11px;line-height:1.5;border-top:1px solid #f3f4f6;">
            <p style="margin:16px 0 0 0;">Email tự động — không trả lời trực tiếp.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
HTML;

  if (!function_exists('sendMail')) {
    return false;
  }
  return (bool)sendMail($toEmail, $subject, $body);
}

function password_reset_token_hash(string $token): string
{
  return hash('sha256', $token);
}

function password_reset_build_link(string $token): string
{
  $path = route_url('auth', 'reset', ['token' => $token]);

  // Tạo link tuyệt đối để click được trong email.
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'] ?? '';
  if (is_string($host) && $host !== '') {
    return $scheme . '://' . $host . $path;
  }
  return $path;
}

/**
 * Tạo token reset cho email (nếu email tồn tại).
 * Trả về link reset (string) hoặc null nếu không tạo được / không tồn tại.
 */
function create_password_reset_for_email(string $email): ?string
{
  $email = strtolower(trim($email));
  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return null;
  }

  $stmt = db()->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
  $stmt->execute([$email]);
  $row = $stmt->fetch();
  if (!$row) {
    return null;
  }
  $userId = (int)$row['id'];

  // Xoá các token cũ của user để giảm rủi ro rò rỉ link cũ.
  $del = db()->prepare('DELETE FROM password_resets WHERE user_id = ?');
  $del->execute([$userId]);

  $token = bin2hex(random_bytes(32)); // 64 hex chars
  $tokenHash = password_reset_token_hash($token);

  $insert = db()->prepare(
    'INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))'
  );
  $insert->execute([$userId, $tokenHash]);

  $link = password_reset_build_link($token);
  // Gửi mail nếu bật MAIL_ENABLED.
  send_password_reset_email($email, $link);
  return $link;
}

/**
 * Trả về user_id nếu token hợp lệ, ngược lại null.
 */
function find_user_id_by_password_reset_token(string $token): ?int
{
  $token = trim($token);
  if ($token === '' || strlen($token) !== 64 || !ctype_xdigit($token)) {
    return null;
  }
  $hash = password_reset_token_hash($token);

  $stmt = db()->prepare(
    'SELECT user_id FROM password_resets WHERE token_hash = ? AND used_at IS NULL AND expires_at > NOW() LIMIT 1'
  );
  $stmt->execute([$hash]);
  $row = $stmt->fetch();
  if (!$row) {
    return null;
  }
  return (int)$row['user_id'];
}

/**
 * Đổi mật khẩu theo token. Thành công trả true.
 */
function consume_password_reset_token(string $token, string $newPassword): bool
{
  $uid = find_user_id_by_password_reset_token($token);
  if (!$uid) {
    return false;
  }

  $newPassword = trim($newPassword);
  if ($newPassword === '' || strlen($newPassword) < 6) {
    return false;
  }

  $hash = password_hash($newPassword, PASSWORD_DEFAULT);

  // Transaction để đảm bảo đổi pass + đánh dấu used đồng bộ.
  $pdo = db();
  $pdo->beginTransaction();
  try {
    $up = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $up->execute([$hash, $uid]);

    $tokenHash = password_reset_token_hash(trim($token));
    $mark = $pdo->prepare('UPDATE password_resets SET used_at = NOW() WHERE token_hash = ? AND user_id = ?');
    $mark->execute([$tokenHash, $uid]);

    // Vô hiệu hoá mọi token khác của user (phòng trường hợp còn tồn tại).
    $del = $pdo->prepare('DELETE FROM password_resets WHERE user_id = ? AND used_at IS NULL');
    $del->execute([$uid]);

    $pdo->commit();
    return true;
  } catch (Throwable $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    throw $e;
  }
}

