-- Database: shoe_store
-- Tạo toàn bộ bảng theo yêu cầu (MySQL thuần).
-- Chạy lệnh này trong phpMyAdmin/MySQL client.

CREATE DATABASE IF NOT EXISTS shoe_store
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE shoe_store;

-- USERS: phân quyền admin/user
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CATEGORIES: loại giày
CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PRODUCTS: sản phẩm
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT NULL,
  image_path VARCHAR(255) NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock_qty INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_products_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CART: giỏ hàng (1 dòng cho mỗi user + product + size)
CREATE TABLE IF NOT EXISTS cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  shoe_size TINYINT UNSIGNED NOT NULL DEFAULT 40,
  quantity INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_cart_user_product_size (user_id, product_id, shoe_size),
  CONSTRAINT fk_cart_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_cart_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ORDERS: đơn hàng
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  buyer_phone VARCHAR(20) NULL,
  addr_house VARCHAR(120) NULL,
  addr_hamlet VARCHAR(120) NULL,
  addr_commune VARCHAR(120) NULL,
  addr_province VARCHAR(120) NULL,
  payment_method ENUM('momo','vnpay','cod') NOT NULL DEFAULT 'cod',
  total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status ENUM('paid','cancelled','pending') NOT NULL DEFAULT 'paid',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ORDER_ITEMS: chi tiết đơn hàng (có quantity và thời gian tạo)
CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  shoe_size TINYINT UNSIGNED NOT NULL DEFAULT 40,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_order_items_order
    FOREIGN KEY (order_id) REFERENCES orders(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- MOMO_TRANSACTIONS: lưu request/response sandbox để đối soát callback
CREATE TABLE IF NOT EXISTS momo_transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  request_id VARCHAR(80) NOT NULL,
  momo_order_id VARCHAR(80) NOT NULL,
  amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status ENUM('initiated','paid','failed') NOT NULL DEFAULT 'initiated',
  pay_url VARCHAR(500) NULL,
  deeplink VARCHAR(500) NULL,
  qr_code_url VARCHAR(500) NULL,
  trans_id VARCHAR(80) NULL,
  pay_type VARCHAR(50) NULL,
  last_result_code INT NULL,
  raw_create_response LONGTEXT NULL,
  raw_return_payload LONGTEXT NULL,
  raw_ipn_payload LONGTEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_momo_order (order_id),
  UNIQUE KEY uniq_momo_request (request_id),
  UNIQUE KEY uniq_momo_order_code (momo_order_id),
  CONSTRAINT fk_momo_transactions_order
    FOREIGN KEY (order_id) REFERENCES orders(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- VNPAY_TRANSACTIONS: lưu giao dịch sandbox
CREATE TABLE IF NOT EXISTS vnpay_transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  txn_ref VARCHAR(80) NOT NULL,
  amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status ENUM('initiated','paid','failed') NOT NULL DEFAULT 'initiated',
  payment_url VARCHAR(500) NULL,
  bank_code VARCHAR(50) NULL,
  bank_tran_no VARCHAR(100) NULL,
  transaction_no VARCHAR(100) NULL,
  response_code VARCHAR(10) NULL,
  transaction_status VARCHAR(10) NULL,
  raw_return_payload LONGTEXT NULL,
  raw_ipn_payload LONGTEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_vnpay_order (order_id),
  UNIQUE KEY uniq_vnpay_txn_ref (txn_ref),
  CONSTRAINT fk_vnpay_transactions_order
    FOREIGN KEY (order_id) REFERENCES orders(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PASSWORD_RESETS: quên mật khẩu (token reset có hạn)
CREATE TABLE IF NOT EXISTS password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_password_resets_token_hash (token_hash),
  KEY idx_password_resets_user_id (user_id),
  CONSTRAINT fk_password_resets_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed categories (tuỳ chọn)
INSERT IGNORE INTO categories (name) VALUES
  ('Sneakers'),
  ('Sandals'),
  ('Boots'),
  ('Chạy bộ & gym'),
  ('Công sở'),
  ('Streetwear');

