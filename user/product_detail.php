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

$ratingSummary = fetch_product_rating_summary($id);
$reviews = fetch_product_reviews($id);

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
            <label class="text-sm text-gray-700 mb-1">Size</label>
            <select
              name="shoe_size"
              class="w-28 border border-gray-200 rounded px-3 py-2 bg-white"
              required
              <?= ((int)$product['stock_qty'] <= 0) ? 'disabled' : '' ?>
            >
              <?php foreach (get_shoe_sizes() as $size): ?>
                <option value="<?= (int)$size ?>" <?= ((int)$size === 40) ? 'selected' : '' ?>>
                  <?= (int)$size ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

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

        <div class="mt-8 border-t pt-4">
          <?php $avgRounded = (int)round((float)($ratingSummary['avg_rating'] ?? 0)); ?>
          <div class="flex items-center justify-between gap-4">
            <div>
              <div class="text-sm text-gray-500">Đánh giá trung bình</div>
              <div class="flex items-center gap-2 mt-1">
                <div class="text-yellow-600 font-semibold">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span style="font-size:18px;"><?= ((int)$i <= $avgRounded) ? '&#9733;' : '&#9734;' ?></span>
                  <?php endfor; ?>
                </div>
                <div class="text-sm text-gray-700">
                  <?= number_format((float)($ratingSummary['avg_rating'] ?? 0), 1, ',', '.') ?>/5
                </div>
              </div>
              <div class="text-xs text-gray-500 mt-1">
                <?= (int)($ratingSummary['review_count'] ?? 0) ?> đánh giá
              </div>
            </div>
          </div>

          <div class="mt-4">
            <h2 class="font-semibold text-base mb-2">Bình luận</h2>
            <?php if (!$reviews): ?>
              <div class="text-sm text-gray-500">Chưa có đánh giá nào.</div>
            <?php else: ?>
              <div class="space-y-3">
                <?php foreach ($reviews as $r): ?>
                  <div class="border rounded p-3 bg-white">
                    <div class="flex items-center justify-between gap-3">
                      <div class="font-medium text-sm">
                        <?= e($r['user_name'] ?? 'Khách') ?>
                      </div>
                      <?php $rRounded = (int)round((float)($r['rating'] ?? 0)); ?>
                      <div class="text-yellow-600 text-sm">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                          <span style="font-size:14px;"><?= ((int)$i <= $rRounded) ? '&#9733;' : '&#9734;' ?></span>
                        <?php endfor; ?>
                      </div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                      <?= e(date('d/m/Y', strtotime((string)($r['created_at'] ?? '')))) ?>
                    </div>
                    <?php if (!empty($r['comment'])): ?>
                      <div class="mt-2 text-sm text-gray-700 leading-relaxed">
                        <?= nl2br(e($r['comment'])) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>

        </div>
      </div>

      <div class="mt-5">
        <a href="<?= e(app_url('user/index.php')) ?>" class="text-sm text-blue-700 hover:underline">← Quay lại</a>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

