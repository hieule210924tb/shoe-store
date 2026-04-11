# Shoe Store (PHP thuần + MySQL + Tailwind)

Website bán giày đơn giản để học.

## 1) Cài đặt MySQL
1. Mở phpMyAdmin / MySQL client.
2. Chạy file `database.sql`.

## 2) Cấu hình PHP
Mở `config/config.php` và chỉnh các biến:
- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

`BASE_URL` dự án tự suy ra từ `REQUEST_URI`, nên thường không cần chỉnh thủ công.

## 3) Tạo tài khoản (admin/user)
- Trang đăng ký tại `auth/register.php`.
- Lần đăng ký đầu tiên hệ thống sẽ tự gán quyền `admin` (nếu chưa có admin nào).

## 4) Chạy dự án
Truy cập `http://localhost/shoe_store/`

