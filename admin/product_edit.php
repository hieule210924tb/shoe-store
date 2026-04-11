<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

$pageTitle = 'Admin - Sửa sản phẩm';

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

  return 'assets/images/products/' . $filename;
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
  set_flash('error', 'ID sản phẩm không hợp lệ.');
  redirect('admin/products.php');
}

$product = admin_get_product($product_id);
if (!$product) {
  set_flash('error', 'Không tìm thấy sản phẩm.');
  redirect('admin/products.php');
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
    redirect('admin/product_edit.php?id=' . $product_id);
  }
  if ($name === '' || strlen($name) < 2) {
    set_flash('error', 'Tên sản phẩm không hợp lệ.');
    redirect('admin/product_edit.php?id=' . $product_id);
  }

  $image_path = $product['image_path']; // mặc định giữ ảnh cũ
  try {
    if (!empty($_FILES['image'])) {
      $newImage = upload_product_image_or_null($_FILES['image']);
      if ($newImage !== null) {
        $image_path = $newImage;
      }
    }

    admin_update_product($product_id, [
      'category_id' => $category_id,
      'name' => $name,
      'description' => $description !== '' ? $description : null,
      'image_path' => $image_path,
      'price' => (float)$price,
      'stock_qty' => (int)$stock_qty,
    ]);

    set_flash('success', 'Đã cập nhật sản phẩm.');
    redirect('admin/products.php');
  } catch (Throwable $e) {
    set_flash('error', $e->getMessage() ?: 'Lỗi cập nhật sản phẩm.');
    redirect('admin/product_edit.php?id=' . $product_id);
  }
}

$stats = admin_stats();
$adminActive = 'products';
$adminHeading = 'Sửa sản phẩm';
$adminBreadcrumbs = [
  ['label' => 'Trang chủ', 'url' => route_url('admin', 'dashboard')],
  ['label' => 'Sản phẩm', 'url' => route_url('admin', 'products')],
  ['label' => 'Sửa', 'url' => null],
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
          <?php $selected = ((int)$c['id'] === (int)$product['category_id']); ?>
          <option value="<?= (int)$c['id'] ?>" <?= $selected ? 'selected' : '' ?>>
            <?= e($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Tên sản phẩm</label>
      <input name="name" class="w-full border border-gray-200 rounded px-3 py-2" required value="<?= e($product['name'] ?? '') ?>">
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Mô tả</label>
      <textarea name="description" rows="4" class="w-full border border-gray-200 rounded px-3 py-2"><?= e($product['description'] ?? '') ?></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm text-gray-700 mb-1">Giá (VND)</label>
        <input name="price" type="number" min="0" class="w-full border border-gray-200 rounded px-3 py-2" required value="<?= (int)($product['price'] ?? 0) ?>">
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Tồn kho</label>
        <input name="stock_qty" type="number" min="0" class="w-full border border-gray-200 rounded px-3 py-2" required value="<?= (int)($product['stock_qty'] ?? 0) ?>">
      </div>
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Ảnh hiện tại</label>
      <div class="w-40 h-28 bg-gray-100 rounded overflow-hidden flex items-center justify-center mb-2">
        <?php if (!empty($product['image_path'])): ?>
          <img src="<?= e(app_url($product['image_path'])) ?>" alt="<?= e($product['name']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
          <div class="text-xs text-gray-400">No image</div>
        <?php endif; ?>
      </div>
      <label class="block text-sm text-gray-700 mb-1">Upload ảnh mới (tuỳ chọn)</label>
      <input type="file" name="image" accept="image/*" class="w-full">
    </div>

    <button type="submit" class="w-full bg-blue-700 text-white rounded px-4 py-3 hover:bg-blue-800">
      Lưu thay đổi
    </button>
  </form>
</div>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>

