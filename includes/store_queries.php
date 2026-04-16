<?php

declare(strict_types=1);

/**
 * Tập hàm truy vấn MySQL dùng chung.
 * (PDO + truy vấn prepared statements)
 */

function fetch_all_categories(): array
{
  return getAll('SELECT id, name FROM categories ORDER BY name ASC');
}

function fetch_category_by_id(int $category_id): ?array
{
  return getOne('SELECT id, name FROM categories WHERE id = ?', [$category_id]);
}

function fetch_product_with_category(int $product_id): ?array
{
  return getOne('
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON c.id = p.category_id
    WHERE p.id = ?
  ', [$product_id]);
}

function fetch_product_rating_summary(int $product_id): array
{
  $row = getOne(
    'SELECT AVG(pr.rating) AS avg_rating, COUNT(*) AS review_count
     FROM product_reviews pr
     WHERE pr.product_id = ?',
    [$product_id]
  );

  return [
    'avg_rating' => (float)($row['avg_rating'] ?? 0),
    'review_count' => (int)($row['review_count'] ?? 0),
  ];
}

function fetch_product_reviews(int $product_id): array
{
  return getAll(
    'SELECT
      pr.rating,
      pr.comment,
      pr.created_at,
      u.name AS user_name
     FROM product_reviews pr
     JOIN users u ON u.id = pr.user_id
     WHERE pr.product_id = ?
     ORDER BY pr.created_at DESC',
    [$product_id]
  );
}

function fetch_user_product_review(int $product_id, int $user_id): ?array
{
  return getOne(
    'SELECT
      pr.rating,
      pr.comment,
      pr.created_at
     FROM product_reviews pr
     WHERE pr.product_id = ? AND pr.user_id = ?
     LIMIT 1',
    [$product_id, $user_id]
  );
}

function upsert_product_review(int $product_id, int $user_id, int $rating, ?string $comment): void
{
  $existing = getOne(
    'SELECT id FROM product_reviews WHERE product_id = ? AND user_id = ? LIMIT 1',
    [$product_id, $user_id]
  );

  if ($existing) {
    updateRow('product_reviews', [
      'rating' => $rating,
      'comment' => $comment,
    ], 'id = :review_id', [':review_id' => (int)$existing['id']]);
    return;
  }

  insertRow('product_reviews', [
    'user_id' => $user_id,
    'product_id' => $product_id,
    'rating' => $rating,
    'comment' => $comment,
  ]);
}

function user_has_paid_product(int $user_id, int $product_id): bool
{
  $row = getOne(
    'SELECT 1
     FROM orders o
     JOIN order_items oi ON oi.order_id = o.id
     WHERE o.user_id = ?
       AND o.status = "paid"
       AND oi.product_id = ?
     LIMIT 1',
    [$user_id, $product_id]
  );

  return $row !== null;
}

function user_has_purchased_product(int $user_id, int $product_id): bool
{
  return user_has_paid_product($user_id, $product_id);
}

function count_products(?int $category_id, string $q = ''): int
{
  $sql = 'SELECT COUNT(*) AS total FROM products p';
  $params = [];
  $where = [];

  if ($category_id) {
    $where[] = 'p.category_id = :category_id';
    $params[':category_id'] = $category_id;
  }

  if ($q !== '') {
    $where[] = 'p.name LIKE :q';
    $params[':q'] = '%' . $q . '%';
  }

  if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
  }

  $row = getOne($sql, $params);
  return (int)($row['total'] ?? 0);
}

function fetch_products(?int $category_id, string $q, int $offset, int $limit): array
{
  $sql = '
    SELECT p.id, p.name, p.description, p.image_path, p.price, p.stock_qty, p.created_at, c.name AS category_name
    FROM products p
    JOIN categories c ON c.id = p.category_id
  ';
  $where = [];
  $params = [];

  if ($category_id) {
    $where[] = 'p.category_id = :category_id';
    $params[':category_id'] = $category_id;
  }

  if ($q !== '') {
    $where[] = 'p.name LIKE :q';
    $params[':q'] = '%' . $q . '%';
  }

  if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
  }

  $sql .= ' ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset';

  // Không dùng helper ở đây vì cần bindValue kiểu int cho limit/offset
  $stmt = db()->prepare($sql);
  foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
  }
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll();
}

function get_shoe_sizes(): array
{
  return [38, 39, 40, 41, 42, 43, 44];
}

function is_valid_shoe_size(int $size): bool
{
  return in_array($size, get_shoe_sizes(), true);
}

function cart_add_item(int $user_id, int $product_id, int $shoe_size, int $qty): void
{
  if ($qty <= 0) {
    return;
  }
  if (!is_valid_shoe_size($shoe_size)) {
    throw new RuntimeException('Size giày không hợp lệ.');
  }

  // Kiểm tra sản phẩm tồn tại
  $p = getOne('SELECT id FROM products WHERE id = ? LIMIT 1', [$product_id]);
  if (!$p) {
    throw new RuntimeException('Sản phẩm không tồn tại.');
  }

  // Update nếu đã có cùng product + size, ngược lại insert mới
  $existing = getOne(
    'SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND shoe_size = ? LIMIT 1',
    [$user_id, $product_id, $shoe_size]
  );

  if ($existing) {
    // dùng query trực tiếp để tăng quantity (updateRow không hỗ trợ biểu thức quantity + ?)
    getRows('UPDATE cart SET quantity = quantity + ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [$qty, (int)$existing['id']]);
  } else {
    insertRow('cart', [
      'user_id' => $user_id,
      'product_id' => $product_id,
      'shoe_size' => $shoe_size,
      'quantity' => $qty,
    ]);
  }
}

function cart_get_items(int $user_id): array
{
  return getAll('
    SELECT
      c.id AS cart_id,
      c.product_id,
      c.shoe_size,
      c.quantity,
      p.name,
      p.image_path,
      p.price,
      p.stock_qty
    FROM cart c
    JOIN products p ON p.id = c.product_id
    WHERE c.user_id = ?
  ', [$user_id]);
}

function cart_update_item(int $user_id, int $cart_id, int $shoe_size, int $quantity): void
{
  if ($cart_id <= 0) {
    return;
  }
  if (!is_valid_shoe_size($shoe_size)) {
    throw new RuntimeException('Size giày không hợp lệ.');
  }
  if ($quantity <= 0) {
    cart_remove_item($user_id, $cart_id);
    return;
  }
  getRows(
    'UPDATE cart SET shoe_size = ?, quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ? AND id = ?',
    [$shoe_size, $quantity, $user_id, $cart_id]
  );
}

function cart_remove_item(int $user_id, int $cart_id): void
{
  deleteRow('cart', 'user_id = ? AND id = ?', [$user_id, $cart_id]);
}

function cart_create_pending_order_from_cart(int $user_id, array $buyer, string $payment_method = 'momo'): int
{
  $pdo = db();
  $pdo->beginTransaction();

  try {
    // Lấy cart + thông tin sản phẩm để kiểm tra stock & tính giá
    $stmt = $pdo->prepare('
      SELECT c.product_id, c.shoe_size, c.quantity, p.price, p.stock_qty
      FROM cart c
      JOIN products p ON p.id = c.product_id
      WHERE c.user_id = ?
    ');
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll();

    if (!$items) {
      throw new RuntimeException('Giỏ hàng của bạn đang trống.');
    }

    $total = 0.0;
    foreach ($items as $it) {
      $qty = (int)$it['quantity'];
      $stock = (int)$it['stock_qty'];
      if ($stock < $qty) {
        throw new RuntimeException('Một số sản phẩm không đủ tồn kho.');
      }
      $total += ((float)$it['price']) * $qty;
    }

    // Tạo order ở trạng thái chờ xác nhận từ cổng thanh toán.
    $orderStmt = $pdo->prepare('
      INSERT INTO orders (
        user_id, buyer_phone, addr_house, addr_hamlet, addr_commune, addr_province,
        payment_method, total_amount, status
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $orderStmt->execute([
      $user_id,
      $buyer['buyer_phone'] ?? null,
      $buyer['addr_house'] ?? null,
      $buyer['addr_hamlet'] ?? null,
      $buyer['addr_commune'] ?? null,
      $buyer['addr_province'] ?? null,
      $payment_method,
      $total,
      'pending',
    ]);
    $order_id = (int)$pdo->lastInsertId();

    // Snapshot order_items để còn đối soát khi MoMo callback về.
    $insertItem = $pdo->prepare('
      INSERT INTO order_items (order_id, product_id, shoe_size, quantity, unit_price)
      VALUES (?, ?, ?, ?, ?)
    ');

    foreach ($items as $it) {
      $pid = (int)$it['product_id'];
      $shoeSize = (int)$it['shoe_size'];
      $qty = (int)$it['quantity'];
      $price = (float)$it['price'];

      $insertItem->execute([$order_id, $pid, $shoeSize, $qty, $price]);
    }

    $pdo->commit();
    return $order_id;
  } catch (Throwable $e) {
    $pdo->rollBack();
    throw $e;
  }
}

function cart_create_order_from_cart(int $user_id): int
{
  $orderId = cart_create_pending_order_from_cart($user_id, [], 'cod');
  mark_order_paid($orderId);
  return $orderId;
}

function fetch_order_by_id(int $order_id): ?array
{
  return getOne('
    SELECT
      id, user_id, buyer_phone, addr_house, addr_hamlet, addr_commune, addr_province,
      payment_method,
      total_amount, status, created_at
    FROM orders
    WHERE id = ?
    LIMIT 1
  ', [$order_id]);
}

function is_valid_payment_method(string $payment_method): bool
{
  return in_array($payment_method, ['momo', 'vnpay', 'cod'], true);
}

function fetch_order_items(int $order_id): array
{
  return getAll('
    SELECT product_id, shoe_size, quantity, unit_price
    FROM order_items
    WHERE order_id = ?
    ORDER BY id ASC
  ', [$order_id]);
}

function create_momo_transaction(int $order_id, string $request_id, string $momo_order_id, float $amount, array $response): void
{
  insertRow('momo_transactions', [
    'order_id' => $order_id,
    'request_id' => $request_id,
    'momo_order_id' => $momo_order_id,
    'amount' => $amount,
    'status' => 'initiated',
    'pay_url' => (string)($response['payUrl'] ?? ''),
    'deeplink' => (string)($response['deeplink'] ?? ''),
    'qr_code_url' => (string)($response['qrCodeUrl'] ?? ''),
    'last_result_code' => (int)($response['resultCode'] ?? -1),
    'raw_create_response' => json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
  ]);
}

function fetch_momo_transaction_by_order_id(int $order_id): ?array
{
  return getOne('SELECT * FROM momo_transactions WHERE order_id = ? LIMIT 1', [$order_id]);
}

function fetch_momo_transaction_by_request_id(string $request_id): ?array
{
  return getOne('SELECT * FROM momo_transactions WHERE request_id = ? LIMIT 1', [$request_id]);
}

function update_momo_transaction_status(int $order_id, string $status, array $payload, ?string $payload_type = null): void
{
  $data = [
    'status' => $status,
    'trans_id' => isset($payload['transId']) ? (string)$payload['transId'] : null,
    'pay_type' => isset($payload['payType']) ? (string)$payload['payType'] : null,
    'last_result_code' => isset($payload['resultCode']) ? (int)$payload['resultCode'] : null,
  ];

  if ($payload_type === 'return') {
    $data['raw_return_payload'] = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  }
  if ($payload_type === 'ipn') {
    $data['raw_ipn_payload'] = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  }

  updateRow('momo_transactions', $data, 'order_id = :where_order_id', [':where_order_id' => $order_id]);
}

function finalize_order_inventory(int $order_id, bool $mark_paid): void
{
  $pdo = db();
  $pdo->beginTransaction();

  try {
    $orderStmt = $pdo->prepare('SELECT id, user_id, status FROM orders WHERE id = ? LIMIT 1 FOR UPDATE');
    $orderStmt->execute([$order_id]);
    $order = $orderStmt->fetch();
    if (!$order) {
      throw new RuntimeException('Không tìm thấy đơn hàng.');
    }
    if ((string)$order['status'] === 'paid') {
      $pdo->commit();
      return;
    }
    if ((string)$order['status'] === 'cancelled') {
      throw new RuntimeException('Đơn hàng đã bị huỷ.');
    }

    $itemsStmt = $pdo->prepare('
      SELECT oi.product_id, oi.shoe_size, oi.quantity, p.stock_qty
      FROM order_items oi
      JOIN products p ON p.id = oi.product_id
      WHERE oi.order_id = ?
      FOR UPDATE
    ');
    $itemsStmt->execute([$order_id]);
    $items = $itemsStmt->fetchAll();
    if (!$items) {
      throw new RuntimeException('Đơn hàng chưa có sản phẩm.');
    }

    foreach ($items as $it) {
      if ((int)$it['stock_qty'] < (int)$it['quantity']) {
        throw new RuntimeException('Một số sản phẩm không đủ tồn kho để xác nhận thanh toán.');
      }
    }

    $updateStock = $pdo->prepare('UPDATE products SET stock_qty = stock_qty - ? WHERE id = ? AND stock_qty >= ?');
    foreach ($items as $it) {
      $qty = (int)$it['quantity'];
      $pid = (int)$it['product_id'];
      $updateStock->execute([$qty, $pid, $qty]);
      if ($updateStock->rowCount() !== 1) {
        throw new RuntimeException('Không thể cập nhật tồn kho.');
      }
    }

    if ($mark_paid) {
      $paidStmt = $pdo->prepare('UPDATE orders SET status = "paid" WHERE id = ?');
      $paidStmt->execute([$order_id]);
    }

    $cartRows = $pdo->prepare('SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND shoe_size = ? LIMIT 1 FOR UPDATE');
    $deleteCart = $pdo->prepare('DELETE FROM cart WHERE id = ?');
    $reduceCart = $pdo->prepare('UPDATE cart SET quantity = quantity - ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');

    foreach (fetch_order_items($order_id) as $item) {
      $cartRows->execute([
        (int)$order['user_id'],
        (int)$item['product_id'],
        (int)$item['shoe_size'],
      ]);
      $cartRow = $cartRows->fetch();
      if (!$cartRow) {
        continue;
      }
      $cartQty = (int)$cartRow['quantity'];
      $orderQty = (int)$item['quantity'];
      if ($cartQty <= $orderQty) {
        $deleteCart->execute([(int)$cartRow['id']]);
      } else {
        $reduceCart->execute([$orderQty, (int)$cartRow['id']]);
      }
    }

    $pdo->commit();
  } catch (Throwable $e) {
    $pdo->rollBack();
    throw $e;
  }
}

function mark_order_paid(int $order_id): void
{
  finalize_order_inventory($order_id, true);
}

function reserve_order_for_cod(int $order_id): void
{
  finalize_order_inventory($order_id, false);
}

function mark_order_cancelled(int $order_id): void
{
  $order = fetch_order_by_id($order_id);
  if (!$order || (string)$order['status'] === 'paid') {
    return;
  }
  updateRow('orders', ['status' => 'cancelled'], 'id = :id', [':id' => $order_id]);
}

function create_vnpay_transaction(int $order_id, string $txn_ref, float $amount, string $payment_url): void
{
  insertRow('vnpay_transactions', [
    'order_id' => $order_id,
    'txn_ref' => $txn_ref,
    'amount' => $amount,
    'status' => 'initiated',
    'payment_url' => $payment_url,
  ]);
}

function fetch_vnpay_transaction_by_txn_ref(string $txn_ref): ?array
{
  return getOne('SELECT * FROM vnpay_transactions WHERE txn_ref = ? LIMIT 1', [$txn_ref]);
}

function update_vnpay_transaction_status(int $order_id, string $status, array $payload, ?string $payload_type = null): void
{
  $data = [
    'status' => $status,
    'bank_code' => isset($payload['vnp_BankCode']) ? (string)$payload['vnp_BankCode'] : null,
    'bank_tran_no' => isset($payload['vnp_BankTranNo']) ? (string)$payload['vnp_BankTranNo'] : null,
    'transaction_no' => isset($payload['vnp_TransactionNo']) ? (string)$payload['vnp_TransactionNo'] : null,
    'response_code' => isset($payload['vnp_ResponseCode']) ? (string)$payload['vnp_ResponseCode'] : null,
    'transaction_status' => isset($payload['vnp_TransactionStatus']) ? (string)$payload['vnp_TransactionStatus'] : null,
  ];

  if ($payload_type === 'return') {
    $data['raw_return_payload'] = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  }
  if ($payload_type === 'ipn') {
    $data['raw_ipn_payload'] = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  }

  updateRow('vnpay_transactions', $data, 'order_id = :where_order_id', [':where_order_id' => $order_id]);
}

function fetch_user_orders(int $user_id, int $offset, int $limit): array
{
  $stmt = db()->prepare('
    SELECT id, total_amount, status, payment_method, created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT ? OFFSET ?
  ');
  // bind kiểu int
  $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
  $stmt->bindValue(2, $limit, PDO::PARAM_INT);
  $stmt->bindValue(3, $offset, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll();
}

function count_user_orders(int $user_id): int
{
  $stmt = db()->prepare('SELECT COUNT(*) AS total FROM orders WHERE user_id = ?');
  $stmt->execute([$user_id]);
  $row = $stmt->fetch();
  return (int)($row['total'] ?? 0);
}

function fetch_order_detail_for_user(int $order_id, int $user_id): ?array
{
  $stmt = db()->prepare('
    SELECT
      id, user_id, buyer_phone, addr_house, addr_hamlet, addr_commune, addr_province,
      payment_method,
      total_amount, status, created_at
    FROM orders
    WHERE id = ? AND user_id = ?
    LIMIT 1
  ');
  $stmt->execute([$order_id, $user_id]);
  $order = $stmt->fetch();
  if (!$order) {
    return null;
  }

  $items = db()->prepare('
    SELECT
      oi.product_id,
      oi.shoe_size,
      oi.quantity,
      oi.unit_price,
      p.name,
      p.image_path
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
    ORDER BY oi.id ASC
  ');
  $items->execute([$order_id]);

  return [
    'order' => $order,
    'items' => $items->fetchAll(),
  ];
}

function admin_get_products(int $offset, int $limit, ?string $q = null): array
{
  $sql = '
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON c.id = p.category_id
  ';
  $params = [];
  if ($q !== null && trim($q) !== '') {
    $sql .= ' WHERE p.name LIKE :q';
    $params[':q'] = '%' . trim($q) . '%';
  }
  $sql .= ' ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset';

  $stmt = db()->prepare($sql);
  foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
  }
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll();
}

function admin_count_products(?string $q = null): int
{
  $sql = 'SELECT COUNT(*) AS total FROM products';
  $params = [];
  if ($q !== null && trim($q) !== '') {
    $sql .= ' WHERE name LIKE :q';
    $params[':q'] = '%' . trim($q) . '%';
  }
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  $row = $stmt->fetch();
  return (int)($row['total'] ?? 0);
}

function admin_get_product(int $product_id): ?array
{
  $stmt = db()->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
  $stmt->execute([$product_id]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function admin_create_product(array $data): int
{
  $stmt = db()->prepare('
    INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
    VALUES (:category_id, :name, :description, :image_path, :price, :stock_qty)
  ');
  $stmt->execute([
    ':category_id' => (int)$data['category_id'],
    ':name' => $data['name'],
    ':description' => $data['description'] ?? null,
    ':image_path' => $data['image_path'] ?? null,
    ':price' => (float)$data['price'],
    ':stock_qty' => (int)$data['stock_qty'],
  ]);
  return (int)db()->lastInsertId();
}

function admin_update_product(int $product_id, array $data): void
{
  // update không bắt buộc image_path nếu không upload
  $stmt = db()->prepare('
    UPDATE products
    SET category_id = :category_id,
        name = :name,
        description = :description,
        image_path = :image_path,
        price = :price,
        stock_qty = :stock_qty
    WHERE id = :id
  ');
  $stmt->execute([
    ':id' => $product_id,
    ':category_id' => (int)$data['category_id'],
    ':name' => $data['name'],
    ':description' => $data['description'] ?? null,
    ':image_path' => $data['image_path'] ?? null,
    ':price' => (float)$data['price'],
    ':stock_qty' => (int)$data['stock_qty'],
  ]);
}

function admin_delete_product(int $product_id): void
{
  $stmt = db()->prepare('DELETE FROM products WHERE id = ?');
  $stmt->execute([$product_id]);
}

function admin_top_products(): array
{
  $stmt = db()->query('
    SELECT
      p.id,
      p.name,
      SUM(oi.quantity) AS total_qty,
      SUM(oi.quantity * oi.unit_price) AS total_revenue
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    JOIN orders o ON o.id = oi.order_id
    WHERE o.status = "paid"
    GROUP BY p.id, p.name
    ORDER BY total_qty DESC
    LIMIT 5
  ');
  return $stmt->fetchAll();
}

function admin_stats(): array
{
  $totalOrders = (int)db()->query('SELECT COUNT(*) AS total FROM orders WHERE status = "paid"')->fetch()['total'];
  $totalRevenueRow = db()->query('SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders WHERE status = "paid"')->fetch();
  $totalRevenue = (float)($totalRevenueRow['total'] ?? 0);
  $totalUsers = (int)db()->query('SELECT COUNT(*) AS total FROM users')->fetch()['total'];
  $totalProducts = (int)db()->query('SELECT COUNT(*) AS total FROM products')->fetch()['total'];
  $pendingOrders = (int)db()->query('SELECT COUNT(*) AS total FROM orders WHERE status = "pending"')->fetch()['total'];
  return [
    'total_orders' => $totalOrders,
    'total_revenue' => $totalRevenue,
    'total_users' => $totalUsers,
    'total_products' => $totalProducts,
    'pending_orders' => $pendingOrders,
    'top_products' => admin_top_products(),
  ];
}

function admin_count_users(?string $q = null): int
{
  $sql = 'SELECT COUNT(*) AS total FROM users';
  $params = [];
  if ($q !== null && trim($q) !== '') {
    $sql .= ' WHERE name LIKE :q OR email LIKE :q';
    $params[':q'] = '%' . trim($q) . '%';
  }
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  return (int)$stmt->fetch()['total'];
}

function admin_get_users(int $offset, int $limit, ?string $q = null): array
{
  $sql = 'SELECT id, name, email, role, created_at FROM users';
  $params = [];
  if ($q !== null && trim($q) !== '') {
    $sql .= ' WHERE name LIKE :q OR email LIKE :q';
    $params[':q'] = '%' . trim($q) . '%';
  }
  $sql .= ' ORDER BY id DESC LIMIT :lim OFFSET :off';
  $stmt = db()->prepare($sql);
  foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
  }
  $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
  $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll();
}

function admin_set_user_role(int $userId, string $role): void
{
  if (!in_array($role, ['admin', 'user'], true)) {
    return;
  }
  $stmt = db()->prepare('UPDATE users SET role = ? WHERE id = ?');
  $stmt->execute([$role, $userId]);
}

function admin_get_categories(): array
{
  return fetch_all_categories();
}

function admin_create_category(string $name): void
{
  $stmt = db()->prepare('INSERT INTO categories (name) VALUES (?)');
  $stmt->execute([$name]);
}

function admin_delete_category(int $id): void
{
  // NOTE: foreign key RESTRICT sẽ ngăn xoá nếu đang có products.
  $stmt = db()->prepare('DELETE FROM categories WHERE id = ?');
  $stmt->execute([$id]);
}

/**
 * ADMIN: Đếm số đơn hàng (có thể lọc theo search/status).
 */
function admin_count_orders(?string $q = null, ?string $status = null): int
{
  $sql = '
    SELECT COUNT(*) AS total
    FROM orders o
    JOIN users u ON u.id = o.user_id
  ';
  $params = [];
  $where = [];

  if ($status !== null && in_array($status, ['paid', 'pending', 'cancelled'], true)) {
    $where[] = 'o.status = :status';
    $params[':status'] = $status;
  }

  if ($q !== null && trim($q) !== '') {
    $where[] = '(u.name LIKE :q OR u.email LIKE :q)';
    $params[':q'] = '%' . trim($q) . '%';
  }

  if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
  }

  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  $row = $stmt->fetch();
  return (int)($row['total'] ?? 0);
}

/**
 * ADMIN: Lấy danh sách đơn hàng (phân trang).
 */
function admin_get_orders(int $offset, int $limit, ?string $q = null, ?string $status = null): array
{
  $sql = '
    SELECT
      o.id,
      o.total_amount,
      o.status,
      o.created_at,
      u.name AS user_name,
      u.email AS user_email
    FROM orders o
    JOIN users u ON u.id = o.user_id
  ';
  $params = [];
  $where = [];

  if ($status !== null && in_array($status, ['paid', 'pending', 'cancelled'], true)) {
    $where[] = 'o.status = :status';
    $params[':status'] = $status;
  }

  if ($q !== null && trim($q) !== '') {
    $where[] = '(u.name LIKE :q OR u.email LIKE :q)';
    $params[':q'] = '%' . trim($q) . '%';
  }

  if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
  }

  $sql .= ' ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset';

  $stmt = db()->prepare($sql);
  foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
  }
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();

  return $stmt->fetchAll();
}

/**
 * ADMIN: Lấy chi tiết 1 đơn hàng + danh sách items.
 */
function admin_fetch_order_detail(int $order_id): ?array
{
  $orderStmt = db()->prepare('
    SELECT
      o.id,
      o.user_id,
      o.buyer_phone,
      o.addr_house,
      o.addr_hamlet,
      o.addr_commune,
      o.addr_province,
      o.payment_method,
      o.total_amount,
      o.status,
      o.created_at,
      u.name AS user_name,
      u.email AS user_email
    FROM orders o
    JOIN users u ON u.id = o.user_id
    WHERE o.id = ?
    LIMIT 1
  ');
  $orderStmt->execute([$order_id]);
  $order = $orderStmt->fetch();
  if (!$order) {
    return null;
  }

  $itemsStmt = db()->prepare('
    SELECT
      oi.product_id,
      oi.shoe_size,
      oi.quantity,
      oi.unit_price,
      p.name AS product_name,
      p.image_path
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
    ORDER BY oi.id ASC
  ');
  $itemsStmt->execute([$order_id]);

  return [
    'order' => $order,
    'items' => $itemsStmt->fetchAll(),
  ];
}

