<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

$pageTitle = 'Admin - Thêm sản phẩm';

function upload_product_image_or_null(array $file): ?string
{
  if (empty($file) || !isset($file['tmp_name'])) {
    return null;
  }
  if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    return null;
  }

  $size = (int)($file['size'] ?? 0);
  if ($size <= 0) {
    return null;
  }

  // Giới hạn ~2MB (tuỳ bạn chỉnh)
  if ($size > 2 * 1024 * 1024) {
    throw new RuntimeException('Ảnh quá lớn. Vui lòng chọn ảnh <= 2MB.');
  }

  $ext = strtolower(pathinfo((string)($file['name'] ?? ''), PATHINFO_EXTENSION));
  $allowed = ['jpg', 'jpeg', 'png', 'webp'];
  if (!in_array($ext, $allowed, true)) {
    throw new RuntimeException('Định dạng ảnh không hợp lệ (chỉ JPG/PNG/WEBP).');
  }

  $uploadDir = __DIR__ . '/../assets/images/products';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $filename = uniqid('prod_', true) . '.' . $ext;
  $target = $uploadDir . '/' . $filename;

  if (!move_uploaded_file((string)$file['tmp_name'], $target)) {
    throw new RuntimeException('Không thể upload ảnh.');
  }

  // Lưu đường dẫn tương đối để app_url xử lý
  return 'assets/images/products/' . $filename;
}

$categories = admin_get_categories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category_id = get_int('category_id', $_POST, 0);
  $name = get_str('name', $_POST);
  $description = get_str('description', $_POST);
  $price = get_int('price', $_POST, 0);
  $stock_qty = get_int('stock_qty', $_POST, 0);

  if ($category_id <= 0) {
    set_flash('error', 'Vui lòng chọn danh mục.');
    redirect('admin/product_add.php');
  }
  if ($name === '' || strlen($name) < 2) {
    set_flash('error', 'Tên sản phẩm không hợp lệ.');
    redirect('admin/product_add.php');
  }
  if ($price < 0) {
    set_flash('error', 'Giá không hợp lệ.');
    redirect('admin/product_add.php');
  }
  if ($stock_qty < 0) {
    set_flash('error', 'Tồn kho không hợp lệ.');
    redirect('admin/product_add.php');
  }

  try {
    $image_path = null;
    if (!empty($_FILES['image'])) {
      $image_path = upload_product_image_or_null($_FILES['image']);
    }

    admin_create_product([
      'category_id' => $category_id,
      'name' => $name,
      'description' => $description !== '' ? $description : null,
      'image_path' => $image_path,
      'price' => (float)$price,
      'stock_qty' => (int)$stock_qty,
    ]);

    set_flash('success', 'Đã thêm sản phẩm thành công.');
    redirect('admin/products.php');
  } catch (Throwable $e) {
    set_flash('error', $e->getMessage() ?: 'Lỗi thêm sản phẩm.');
    redirect('admin/product_add.php');
  }
}

$stats = admin_stats();
$adminActive = 'products';
$adminHeading = 'Thêm sản phẩm';
$adminBreadcrumbs = [
  ['label' => 'Trang chủ', 'url' => route_url('admin', 'dashboard')],
  ['label' => 'Sản phẩm', 'url' => route_url('admin', 'products')],
  ['label' => 'Thêm mới', 'url' => null],
];
$adminNavBadge = (int)$stats['pending_orders'];

require_once __DIR__ . '/includes/layout_start.php';
?>

<div class="mb-3">
  <a href="<?= e(route_url('admin', 'products')) ?>" class="btn btn-outline-secondary btn-sm">← Quay lại danh sách</a>
</div>

<div class="mt-6 bg-white border rounded-lg p-4 md:p-6">
  <form method="POST" enctype="multipart/form-data" class="space-y-4">
    <div>
      <label class="block text-sm text-gray-700 mb-1">Danh mục</label>
      <select name="category_id" class="w-full border border-gray-200 rounded px-3 py-2" required>
        <option value="">-- Chọn danh mục --</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <?php if (!$categories): ?>
        <div class="text-xs text-red-600 mt-2">Bạn chưa có danh mục. Vào `admin/categories.php` để thêm trước.</div>
      <?php endif; ?>
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Tên sản phẩm</label>
      <input name="name" class="w-full border border-gray-200 rounded px-3 py-2" required>
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Mô tả</label>
      <textarea name="description" rows="4" class="w-full border border-gray-200 rounded px-3 py-2" placeholder="Mô tả ngắn..."></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm text-gray-700 mb-1">Giá (VND)</label>
        <input name="price" type="number" min="0" class="w-full border border-gray-200 rounded px-3 py-2" required value="0">
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Tồn kho</label>
        <input name="stock_qty" type="number" min="0" class="w-full border border-gray-200 rounded px-3 py-2" required value="0">
      </div>
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Upload ảnh (JPG/PNG/WEBP, <=2MB)</label>
      <input type="file" name="image" accept="image/*" class="w-full">
    </div>

    <button type="submit" class="w-full bg-blue-700 text-white rounded px-4 py-3 hover:bg-blue-800">
      Lưu sản phẩm
    </button>
  </form>
</div>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>

