<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$pageTitle = 'Giỏ hàng';

$uid = current_user_id();
$items = cart_get_items($uid);

$total = 0.0;
foreach ($items as $it) {
  $total += ((float)$it['price']) * (int)$it['quantity'];
}

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="flex items-center justify-between gap-4">
  <h1 class="text-2xl font-bold">Giỏ hàng</h1>
  <a href="<?= e(app_url('user/index.php')) ?>" class="text-sm text-blue-700 hover:underline">Tiếp tục mua</a>
</div>

<?php if (!$items): ?>
  <div class="mt-6 rounded border bg-white p-6 text-center text-gray-600">
    Giỏ hàng của bạn đang trống.
  </div>
<?php else: ?>
  <div class="mt-6 bg-white border rounded-lg p-4 md:p-6">
    <div class="space-y-4">
      <?php foreach ($items as $it): ?>
        <?php $line = ((float)$it['price']) * (int)$it['quantity']; ?>
        <div class="flex flex-col md:flex-row md:items-center gap-4 border-b last:border-b-0 pb-4 last:pb-0">
          <div class="w-full md:w-28 h-20 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
            <?php if (!empty($it['image_path'])): ?>
              <img src="<?= e(app_url($it['image_path'])) ?>" alt="<?= e($it['name']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <div class="text-gray-400 text-xs">No image</div>
            <?php endif; ?>
          </div>

          <div class="flex-1">
            <div class="font-semibold"><?= e($it['name']) ?></div>
            <div class="text-sm text-gray-600 mt-1">Đơn giá: <span class="font-medium"><?= number_format((float)$it['price'], 0, ',', '.') ?> VND</span></div>
            <div class="text-xs text-gray-500 mt-1">Size: <?= (int)$it['shoe_size'] ?> • Còn lại: <?= (int)$it['stock_qty'] ?></div>
          </div>

          <div class="w-full md:w-64 flex items-end justify-between gap-3">
            <form method="POST" action="<?= e(app_url('user/cart_update.php')) ?>" class="flex items-end gap-2">
              <input type="hidden" name="cart_id" value="<?= (int)$it['cart_id'] ?>">
              <div>
                <label class="text-xs text-gray-600">Size</label>
                <select
                  name="shoe_size"
                  class="w-20 border border-gray-200 rounded px-2 py-1 bg-white"
                >
                  <?php foreach (get_shoe_sizes() as $size): ?>
                    <option value="<?= (int)$size ?>" <?= ((int)$size === (int)$it['shoe_size']) ? 'selected' : '' ?>>
                      <?= (int)$size ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="text-xs text-gray-600">SL</label>
                <input
                  name="quantity"
                  type="number"
                  min="1"
                  max="<?= (int)$it['stock_qty'] ?>"
                  value="<?= (int)$it['quantity'] ?>"
                  class="w-20 border border-gray-200 rounded px-2 py-1"
                >
              </div>
              <button class="px-3 py-2 rounded border bg-white hover:bg-gray-50 text-sm" type="submit">
                Cập nhật
              </button>
            </form>

            <form method="POST" action="<?= e(app_url('user/cart_remove.php')) ?>">
              <input type="hidden" name="cart_id" value="<?= (int)$it['cart_id'] ?>">
              <button class="px-3 py-2 rounded bg-red-600 text-white hover:bg-red-700 text-sm" type="submit">
                Xoá
              </button>
            </form>
          </div>

          <div class="w-full md:w-48 text-right font-bold text-gray-900">
            <?= number_format((float)$line, 0, ',', '.') ?> VND
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
      <div class="text-gray-600">
        Tổng tiền
      </div>
      <div class="text-xl font-bold">
        <?= number_format((float)$total, 0, ',', '.') ?> VND
      </div>
    </div>

    <div class="mt-4">
      <a href="<?= e(app_url('user/checkout.php')) ?>"
         class="block text-center bg-blue-700 text-white rounded px-4 py-3 hover:bg-blue-800">
        Thanh toán (MoMo giả lập)
      </a>
    </div>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

