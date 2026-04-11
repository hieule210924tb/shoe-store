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

function cart_add_item(int $user_id, int $product_id, int $qty): void
{
  if ($qty <= 0) {
    return;
  }

  // Kiểm tra sản phẩm tồn tại
  $p = getOne('SELECT id FROM products WHERE id = ? LIMIT 1', [$product_id]);
  if (!$p) {
    throw new RuntimeException('Sản phẩm không tồn tại.');
  }

  // Update nếu đã có, ngược lại insert mới
  $existing = getOne('SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? LIMIT 1', [$user_id, $product_id]);

  if ($existing) {
    // dùng query trực tiếp để tăng quantity (updateRow không hỗ trợ biểu thức quantity + ?)
    getRows('UPDATE cart SET quantity = quantity + ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [$qty, (int)$existing['id']]);
  } else {
    insertRow('cart', [
      'user_id' => $user_id,
      'product_id' => $product_id,
      'quantity' => $qty,
    ]);
  }
}

function cart_get_items(int $user_id): array
{
  return getAll('
    SELECT
      c.product_id,
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

function cart_update_quantity(int $user_id, int $product_id, int $quantity): void
{
  if ($quantity <= 0) {
    cart_remove_item($user_id, $product_id);
    return;
  }
  getRows(
    'UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ? AND product_id = ?',
    [$quantity, $user_id, $product_id]
  );
}

function cart_remove_item(int $user_id, int $product_id): void
{
  deleteRow('cart', 'user_id = ? AND product_id = ?', [$user_id, $product_id]);
}

function cart_create_order_from_cart(int $user_id): int
{
  $pdo = db();
  $pdo->beginTransaction();

  try {
    // Lấy cart + thông tin sản phẩm để kiểm tra stock & tính giá
    $stmt = $pdo->prepare('
      SELECT c.product_id, c.quantity, p.price, p.stock_qty
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

    // Tạo order
    $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, ?)');
    $orderStmt->execute([$user_id, $total, 'paid']);
    $order_id = (int)$pdo->lastInsertId();

    // Tạo order_items + trừ stock
    $insertItem = $pdo->prepare('
      INSERT INTO order_items (order_id, product_id, quantity, unit_price)
      VALUES (?, ?, ?, ?)
    ');
    $updateStock = $pdo->prepare('
      UPDATE products
      SET stock_qty = stock_qty - ?
      WHERE id = ? AND stock_qty >= ?
    ');

    foreach ($items as $it) {
      $pid = (int)$it['product_id'];
      $qty = (int)$it['quantity'];
      $price = (float)$it['price'];

      $insertItem->execute([$order_id, $pid, $qty, $price]);
      $updateStock->execute([$qty, $pid, $qty]);
    }

    // Xóa cart
    $del = $pdo->prepare('DELETE FROM cart WHERE user_id = ?');
    $del->execute([$user_id]);

    $pdo->commit();
    return $order_id;
  } catch (Throwable $e) {
    $pdo->rollBack();
    throw $e;
  }
}

function fetch_user_orders(int $user_id, int $offset, int $limit): array
{
  $stmt = db()->prepare('
    SELECT id, total_amount, status, created_at
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
    SELECT id, user_id, total_amount, status, created_at
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

