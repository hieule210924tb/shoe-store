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

<div class="card card-outline card-secondary">
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="category_id">Danh mục</label>
        <select name="category_id" id="category_id" class="form-control" required>
          <option value="">-- Chọn danh mục --</option>
          <?php foreach ($categories as $c): ?>
            <?php $selected = ((int)$c['id'] === (int)$product['category_id']); ?>
            <option value="<?= (int)$c['id'] ?>" <?= $selected ? 'selected' : '' ?>>
              <?= e($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (!$categories): ?>
          <small class="form-text text-danger">Chưa có danh mục. Vào <a href="<?= e(route_url('admin', 'categories')) ?>">Danh mục</a> để thêm trước.</small>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="product-name">Tên sản phẩm</label>
        <input name="name" id="product-name" type="text" class="form-control" required value="<?= e($product['name'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label for="product-description">Mô tả</label>
        <textarea name="description" id="product-description" rows="4" class="form-control"><?= e($product['description'] ?? '') ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="product-price">Giá (VND)</label>
          <input name="price" id="product-price" type="number" min="0" class="form-control" required value="<?= (int)($product['price'] ?? 0) ?>">
        </div>
        <div class="form-group col-md-6">
          <label for="product-stock">Tồn kho</label>
          <input name="stock_qty" id="product-stock" type="number" min="0" class="form-control" required value="<?= (int)($product['stock_qty'] ?? 0) ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Ảnh hiện tại</label>
        <div class="mb-3 border rounded bg-light d-flex align-items-center justify-content-center overflow-hidden" style="width: 160px; height: 112px;">
          <?php if (!empty($product['image_path'])): ?>
            <img src="<?= e(app_url($product['image_path'])) ?>" alt="<?= e($product['name']) ?>" class="img-fluid" style="max-height: 112px; width: 100%; object-fit: cover;">
          <?php else: ?>
            <span class="small text-muted">Chưa có ảnh</span>
          <?php endif; ?>
        </div>
        <label for="product-image">Upload ảnh mới (JPG/PNG/WEBP, tối đa 2MB, tuỳ chọn)</label>
        <input type="file" name="image" id="product-image" accept="image/*" class="form-control-file">
      </div>

      <button type="submit" class="btn btn-primary btn-block btn-lg">Lưu thay đổi</button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>

