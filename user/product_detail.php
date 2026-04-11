<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Chi tiết sản phẩm';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  set_flash('error', 'ID sản phẩm không hợp lệ.');
  redirect('user/index.php');
}

$product = fetch_product_with_category($id);
if (!$product) {
  set_flash('error', 'Không tìm thấy sản phẩm.');
  redirect('user/index.php');
}

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="flex flex-col lg:flex-row gap-6">
  <div class="lg:w-1/2">
    <div class="bg-white border rounded-lg overflow-hidden">
      <div class="h-72 bg-gray-100 flex items-center justify-center">
        <?php if (!empty($product['image_path'])): ?>
          <img src="<?= e(app_url($product['image_path'])) ?>" alt="<?= e($product['name']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
          <div class="text-gray-400 text-sm">No image</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="lg:w-1/2">
    <div class="bg-white border rounded-lg p-5">
      <div class="text-sm text-blue-700 font-medium"><?= e($product['category_name']) ?></div>
      <h1 class="text-2xl font-bold mt-1"><?= e($product['name']) ?></h1>
      <div class="mt-3 text-2xl font-bold text-gray-900">
        <?= number_format((float)$product['price'], 0, ',', '.') ?> VND
      </div>
      <div class="mt-2 text-sm text-gray-600">
        Tồn kho: <?= (int)$product['stock_qty'] ?>
      </div>

      <div class="mt-4 text-gray-700 leading-relaxed">
        <?= nl2br(e($product['description'] ?? '')) ?>
      </div>

      <div class="mt-6 border-t pt-4">
        <form method="POST" action="<?= e(app_url('user/cart_add.php')) ?>" class="flex gap-3 items-end">
          <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

          <div class="flex flex-col">
            <label class="text-sm text-gray-700 mb-1">Số lượng</label>
            <input
              name="quantity"
              type="number"
              min="1"
              max="<?= (int)$product['stock_qty'] ?>"
              value="1"
              class="w-28 border border-gray-200 rounded px-3 py-2"
              required
              <?= ((int)$product['stock_qty'] <= 0) ? 'disabled' : '' ?>
            >
          </div>

          <button
            class="px-4 py-2 rounded bg-blue-700 text-white hover:bg-blue-800 disabled:opacity-50"
            type="submit"
            <?= ((int)$product['stock_qty'] <= 0) ? 'disabled' : '' ?>
          >
            Thêm vào giỏ
          </button>
        </form>

        <div class="mt-3 text-xs text-gray-500">
          Bấm thêm vào giỏ để chuẩn bị thanh toán.
        </div>
      </div>

      <div class="mt-5">
        <a href="<?= e(app_url('user/index.php')) ?>" class="text-sm text-blue-700 hover:underline">← Quay lại</a>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

