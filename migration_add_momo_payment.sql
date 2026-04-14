USE shoe_store;

ALTER TABLE orders
  MODIFY COLUMN status ENUM('paid', 'cancelled', 'pending') NOT NULL DEFAULT 'pending';

ALTER TABLE orders
  ADD COLUMN IF NOT EXISTS buyer_phone VARCHAR(20) NULL AFTER user_id,
  ADD COLUMN IF NOT EXISTS addr_house VARCHAR(120) NULL AFTER buyer_phone,
  ADD COLUMN IF NOT EXISTS addr_hamlet VARCHAR(120) NULL AFTER addr_house,
  ADD COLUMN IF NOT EXISTS addr_commune VARCHAR(120) NULL AFTER addr_hamlet,
  ADD COLUMN IF NOT EXISTS addr_province VARCHAR(120) NULL AFTER addr_commune,
  ADD COLUMN IF NOT EXISTS payment_method ENUM('momo', 'vnpay', 'cod') NOT NULL DEFAULT 'cod' AFTER addr_province;

CREATE TABLE IF NOT EXISTS momo_transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  request_id VARCHAR(80) NOT NULL,
  momo_order_id VARCHAR(80) NOT NULL,
  amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status ENUM('initiated', 'paid', 'failed') NOT NULL DEFAULT 'initiated',
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

CREATE TABLE IF NOT EXISTS vnpay_transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  txn_ref VARCHAR(80) NOT NULL,
  amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status ENUM('initiated', 'paid', 'failed') NOT NULL DEFAULT 'initiated',
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
