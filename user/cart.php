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
    <div class="space-y-5">
      <?php foreach ($items as $it): ?>
        <?php $line = ((float)$it['price']) * (int)$it['quantity']; ?>

        <div class="flex flex-col lg:flex-row gap-4 border-b last:border-b-0 pb-4">

          <!-- IMAGE -->
          <div class="w-full lg:w-32 h-24 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
            <?php if (!empty($it['image_path'])): ?>
              <img src="<?= e(app_url($it['image_path'])) ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <span class="text-gray-400 text-xs">No image</span>
            <?php endif; ?>
          </div>

          <!-- INFO -->
          <div class="flex-1">
            <div class="font-semibold text-lg"><?= e($it['name']) ?></div>
            <div class="text-sm text-gray-600 mt-1">
              Giá:
              <span class="font-medium text-red-600">
                <?= number_format((float)$it['price'], 0, ',', '.') ?> VND
              </span>
            </div>
            <div class="text-xs text-gray-500 mt-1">
              Size hiện tại: <?= (int)$it['shoe_size'] ?>
            </div>
          </div>

          <!-- ACTION -->
          <div class="w-full lg:w-auto flex flex-col sm:flex-row lg:flex-col gap-3">

            <!-- FORM UPDATE -->
            <form method="POST" action="<?= e(app_url('user/cart_update.php')) ?>"
              class="flex flex-wrap items-end gap-2">

              <input type="hidden" name="cart_id" value="<?= (int)$it['cart_id'] ?>">

              <div>
                <label class="text-xs text-gray-600">Size</label>
                <select name="shoe_size" class="border border-gray-300 rounded px-2 py-1 bg-white">
                  <?php foreach (get_shoe_sizes() as $size): ?>
                    <option value="<?= $size ?>" <?= $size == $it['shoe_size'] ? 'selected' : '' ?>>
                      <?= $size ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="text-xs text-gray-600">SL</label>
                <input type="number" name="quantity" min="1" max="<?= (int)$it['stock_qty'] ?>"
                  value="<?= (int)$it['quantity'] ?>" class="w-20 border border-gray-300 rounded px-2 py-1">
              </div>

              <button type="submit"
                class="px-3 py-2 rounded bg-yellow-400 hover:bg-yellow-500 text-black text-sm w-full sm:w-auto">
                Cập nhật
              </button>
            </form>

            <!-- FORM DELETE -->
            <form method="POST" action="<?= e(app_url('user/cart_remove.php')) ?>">
              <input type="hidden" name="cart_id" value="<?= (int)$it['cart_id'] ?>">
              <button type="submit"
                class="px-3 py-2 rounded bg-red-600 hover:bg-red-700 text-white text-sm w-full">
                Xoá
              </button>
            </form>

          </div>

          <!-- PRICE -->
          <div class="text-right font-bold text-gray-900 lg:w-40">
            <?= number_format($line, 0, ',', '.') ?> VND
          </div>

        </div>
      <?php endforeach; ?>
    </div>

    <!-- TOTAL -->
    <div class="mt-6 flex justify-end text-lg">
      <span class="mr-2">Tổng tiền:</span>
      <strong class="text-red-600">
        <?= number_format($total, 0, ',', '.') ?> VND
      </strong>
    </div>

    <!-- CHECKOUT -->
    <div class="mt-6">
      <a href="<?= e(app_url('user/checkout.php')) ?>"
        class="block text-center bg-red-700 hover:bg-red-800 text-white rounded px-4 py-3 w-full md:w-1/2 lg:w-1/3 mx-auto">
        Thanh toán
      </a>
    </div>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>