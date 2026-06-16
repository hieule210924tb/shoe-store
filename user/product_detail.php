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

<div class="flex flex-col mt-24 lg:flex-row gap-8">
  <div class="lg:w-1/2 animate-on-scroll">
    <div class="bg-white dark:bg-dark-card border border-gray-100 dark:border-dark-border rounded-2xl overflow-hidden shadow-lg card-hover">
      <div class="h-80 bg-gray-100 dark:bg-gray-800 flex items-center justify-center img-zoom-container relative">
        <?php if (!empty($product['image_path'])): ?>
          <img src="<?= e(app_url($product['image_path'])) ?>" alt="<?= e($product['name']) ?>"
            class="w-full h-full object-contain p-6 transition-transform duration-500 hover:scale-110">
        <?php else: ?>
          <div class="text-gray-400 text-sm">No image</div>
        <?php endif; ?>
        
        <!-- Quick view button overlay -->
        <div class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
          <button class="px-6 py-3 bg-white/95 dark:bg-dark-card rounded-full shadow-lg transform translate-y-4 hover:translate-y-0 transition-all duration-300 hover:scale-105">
            <i class="bi bi-zoom-in mr-2"></i> Phóng to
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="lg:w-1/2 animate-on-scroll" style="animation-delay: 100ms">
    <div class="bg-white dark:bg-dark-card border border-gray-100 dark:border-dark-border rounded-2xl p-6 shadow-lg">
      <div class="text-sm font-semibold text-red-600 dark:text-red-400 uppercase tracking-wide mb-2"><?= e($product['category_name']) ?></div>
      <h1 class="text-3xl font-bold text-gray-900 dark:text-dark-text mb-4"><?= e($product['name']) ?></h1>
      
      <div class="flex items-baseline gap-3 mb-4">
        <div class="text-3xl font-bold text-red-600 dark:text-red-400"><?= number_format((float)$product['price'], 0, ',', '.') ?> VND</div>
        <?php if ((int)$product['stock_qty'] > 0): ?>
          <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-sm font-medium rounded-full">Còn hàng</span>
        <?php else: ?>
          <span class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-sm font-medium rounded-full">Hết hàng</span>
        <?php endif; ?>
      </div>
      
      <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-dark-muted mb-6">
        <i class="bi bi-box-seam"></i>
        <span>Tồn kho: <strong><?= (int)$product['stock_qty'] ?></strong></span>
      </div>

      <div class="text-gray-700 dark:text-dark-text leading-relaxed mb-6">
        <?= nl2br(e($product['description'] ?? '')) ?>
      </div>

      <div class="border-t border-gray-200 dark:border-dark-border pt-6">
        <form method="POST" action="<?= e(app_url('user/cart_add.php')) ?>" class="space-y-4">
          <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
          
          <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
              <label class="text-sm font-semibold text-gray-700 dark:text-dark-text mb-2 block">Size</label>
              <div class="relative">
                <select name="shoe_size" class="w-full border-2 border-gray-200 dark:border-dark-border rounded-xl px-4 py-3 bg-white dark:bg-dark-card focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300 appearance-none cursor-pointer" required
                  <?= ((int)$product['stock_qty'] <= 0) ? 'disabled' : '' ?>>
                  <?php foreach (get_shoe_sizes() as $size): ?>
                    <option value="<?= (int)$size ?>" <?= ((int)$size === 40) ? 'selected' : '' ?>>
                      <?= (int)$size ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
              </div>
            </div>

            <div class="flex-1">
              <label class="text-sm font-semibold text-gray-700 dark:text-dark-text mb-2 block">Số lượng</label>
              <div class="quantity-selector flex items-center border-2 border-gray-200 dark:border-dark-border rounded-xl overflow-hidden">
                <button type="button" class="quantity-minus px-4 py-3 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition border-r border-gray-200 dark:border-dark-border">
                  <i class="bi bi-dash"></i>
                </button>
                <input name="quantity" type="number" min="1" max="<?= (int)$product['stock_qty'] ?>" value="1"
                  class="flex-1 w-full text-center border-0 bg-white dark:bg-dark-card focus:outline-none" required
                  <?= ((int)$product['stock_qty'] <= 0) ? 'disabled' : '' ?>>
                <button type="button" class="quantity-plus px-4 py-3 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition border-l border-gray-200 dark:border-dark-border">
                  <i class="bi bi-plus"></i>
                </button>
              </div>
            </div>
          </div>

          <button class="w-full px-6 py-4 rounded-xl bg-red-600 text-white font-bold text-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 hover:shadow-lg hover:scale-[1.02] btn-ripple flex items-center justify-center gap-2"
            type="submit" <?= ((int)$product['stock_qty'] <= 0) ? 'disabled' : '' ?>>
            <i class="bi bi-bag"></i> Thêm vào giỏ hàng
          </button>
        </form>

        <div class="text-sm text-gray-500 dark:text-dark-muted text-center mt-4">
          <i class="bi bi-info-circle mr-1"></i> Bấm thêm vào giỏ để chuẩn bị thanh toán.
        </div>

        <div class="mt-8 border-t border-gray-200 dark:border-dark-border pt-6">
          <?php $avgRounded = (int)round((float)($ratingSummary['avg_rating'] ?? 0)); ?>
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
              <div class="text-sm text-gray-600 dark:text-dark-muted font-medium mb-2">Đánh giá trung bình</div>
              <div class="flex items-center gap-3">
                <div class="flex text-yellow-400 text-2xl">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="transition-transform hover:scale-125 cursor-pointer"><?= ((int)$i <= $avgRounded) ? '★' : '☆' ?></span>
                  <?php endfor; ?>
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-dark-text">
                  <?= number_format((float)($ratingSummary['avg_rating'] ?? 0), 1, ',', '.') ?>
                </div>
                <div class="text-sm text-gray-500 dark:text-dark-muted">
                  / 5
                </div>
              </div>
              <div class="text-sm text-gray-500 dark:text-dark-muted mt-1">
                <?= (int)($ratingSummary['review_count'] ?? 0) ?> đánh giá
              </div>
            </div>
          </div>

          <div class="animate-on-scroll">
            <h2 class="font-semibold text-lg text-gray-900 dark:text-dark-text mb-4">Bình luận</h2>
            <?php if (!$reviews): ?>
              <div class="text-center py-8 text-gray-500 dark:text-dark-muted">
                <i class="bi bi-chat-square-text text-4xl mb-2 block opacity-50"></i>
                Chưa có đánh giá nào.
              </div>
            <?php else: ?>
              <div class="space-y-4">
                <?php foreach ($reviews as $index => $r): ?>
                  <?php $rRounded = (int)round((float)($r['rating'] ?? 0)); ?>
                  <div class="border border-gray-200 dark:border-dark-border rounded-xl p-4 bg-white dark:bg-dark-card shadow-sm card-hover animate-on-scroll" style="animation-delay: <?= $index * 100 ?>ms">
                    <div class="flex items-start justify-between gap-4">
                      <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                          <i class="bi bi-person text-red-600 dark:text-red-400"></i>
                        </div>
                        <div>
                          <div class="font-semibold text-gray-900 dark:text-dark-text"><?= e($r['user_name'] ?? 'Khách') ?></div>
                          <div class="text-xs text-gray-500 dark:text-dark-muted"><?= e(date('d/m/Y', strtotime((string)($r['created_at'] ?? '')))) ?></div>
                        </div>
                      </div>
                      <div class="flex text-yellow-400 text-sm">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                          <span><?= ((int)$i <= $rRounded) ? '★' : '☆' ?></span>
                        <?php endfor; ?>
                      </div>
                    </div>
                    <?php if (!empty($r['comment'])): ?>
                      <div class="mt-3 text-sm text-gray-700 dark:text-dark-text leading-relaxed">
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

      <div class="mt-6 pt-6 border-t border-gray-200 dark:border-dark-border">
        <a href="<?= e(app_url('user/index.php')) ?>" class="inline-flex items-center text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium transition link-underline">
          <i class="bi bi-arrow-left mr-2"></i> Quay lại trang chủ
        </a>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>