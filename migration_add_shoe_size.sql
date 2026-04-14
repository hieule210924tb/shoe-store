USE shoe_store;

-- 1) Bổ sung cột size ở cart nếu chưa có
ALTER TABLE cart
  ADD COLUMN IF NOT EXISTS shoe_size TINYINT UNSIGNED NOT NULL DEFAULT 40 AFTER product_id;

-- 2) Chuẩn hoá dữ liệu cũ
UPDATE cart
SET shoe_size = 40
WHERE shoe_size IS NULL OR shoe_size = 0;

-- 3) Đổi unique key cart từ (user_id, product_id) sang (user_id, product_id, shoe_size)
ALTER TABLE cart
  DROP INDEX uniq_cart_user_product,
  ADD UNIQUE KEY uniq_cart_user_product_size (user_id, product_id, shoe_size);

-- 4) Bổ sung cột size cho order_items để lưu lịch sử đơn
ALTER TABLE order_items
  ADD COLUMN IF NOT EXISTS shoe_size TINYINT UNSIGNED NOT NULL DEFAULT 40 AFTER product_id;
