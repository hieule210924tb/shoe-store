<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$pageTitle = 'Thanh toán';

$uid = current_user_id();
$items = cart_get_items($uid);

$old = $_SESSION['checkout_buyer'] ?? [];
$old_phone = is_array($old) ? (string)($old['buyer_phone'] ?? '') : '';
$old_house = is_array($old) ? (string)($old['addr_house'] ?? '') : '';
$old_hamlet = is_array($old) ? (string)($old['addr_hamlet'] ?? '') : '';
$old_commune = is_array($old) ? (string)($old['addr_commune'] ?? '') : '';
$old_province = is_array($old) ? (string)($old['addr_province'] ?? '') : '';
$old_payment_method = is_array($old) ? (string)($old['payment_method'] ?? 'momo') : 'momo';

$total = 0.0;
foreach ($items as $it) {
  $total += ((float)$it['price']) * (int)$it['quantity'];
}

if (!$items) {
  set_flash('error', 'Giỏ hàng của bạn đang trống.');
  redirect('user/cart.php');
}

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="mx-auto mt-4 max-w-4xl">
  <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="rounded-xl border bg-white p-5 shadow-sm">
      <div class="mb-4 flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded bg-fuchsia-700 text-sm font-bold uppercase tracking-wide text-white">
          MoMo
        </div>
        <div>
          <h1 class="text-lg font-semibold text-gray-900">Chọn phương thức thanh toán</h1>
          <p class="text-sm text-gray-500">Hỗ trợ MoMo sandbox, VNPay sandbox và thanh toán khi nhận hàng.</p>
        </div>
      </div>

      <div class="space-y-3">
        <?php foreach ($items as $it): ?>
          <div class="flex items-center justify-between gap-3 border-b pb-3 last:border-b-0 last:pb-0">
            <div>
              <div class="font-medium text-gray-900"><?= e($it['name']) ?></div>
              <div class="text-sm text-gray-500">Size <?= (int)$it['shoe_size'] ?> • SL <?= (int)$it['quantity'] ?></div>
            </div>
            <div class="text-sm font-semibold text-gray-900">
              <?= number_format(((float)$it['price']) * (int)$it['quantity'], 0, ',', '.') ?> VND
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-5 flex items-center justify-between border-t pt-4">
        <span class="text-gray-600">Tổng thanh toán</span>
        <span class="text-2xl font-bold text-fuchsia-700"><?= number_format((float)$total, 0, ',', '.') ?> VND</span>
      </div>

      <form method="POST" action="<?= e(app_url('user/checkout_confirm.php')) ?>" class="mt-5 space-y-3">
        <div class="rounded-lg border bg-gray-50 p-4">
          <div class="mb-3 text-sm font-semibold text-gray-900">Thông tin người mua</div>

          <div class="grid grid-cols-1 gap-3">
            <div>
              <label class="block text-xs font-medium text-gray-700">Số điện thoại</label>
              <input
                name="buyer_phone"
                value="<?= e($old_phone) ?>"
                required
                inputmode="tel"
                class="mt-1 w-full rounded border border-gray-200 bg-white px-3 py-2 text-sm"
                placeholder="VD: 09xxxxxxxx"
              >
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
              <div>
                <label class="block text-xs font-medium text-gray-700">Số nhà</label>
                <input
                  name="addr_house"
                  value="<?= e($old_house) ?>"
                  required
                  class="mt-1 w-full rounded border border-gray-200 bg-white px-3 py-2 text-sm"
                  placeholder="VD: 12A"
                >
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700">Thôn</label>
                <input
                  name="addr_hamlet"
                  value="<?= e($old_hamlet) ?>"
                  required
                  class="mt-1 w-full rounded border border-gray-200 bg-white px-3 py-2 text-sm"
                  placeholder="VD: Thôn 3"
                >
              </div>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
              <div>
                <label class="block text-xs font-medium text-gray-700">Xã</label>
                <input
                  name="addr_commune"
                  value="<?= e($old_commune) ?>"
                  required
                  class="mt-1 w-full rounded border border-gray-200 bg-white px-3 py-2 text-sm"
                  placeholder="VD: Xã ABC"
                >
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700">Tỉnh</label>
                <input
                  name="addr_province"
                  value="<?= e($old_province) ?>"
                  required
                  class="mt-1 w-full rounded border border-gray-200 bg-white px-3 py-2 text-sm"
                  placeholder="VD: Hà Nội"
                >
              </div>
            </div>
          </div>
        </div>

        <div class="rounded-lg border bg-gray-50 p-4">
          <div class="mb-3 text-sm font-semibold text-gray-900">Phương thức thanh toán</div>
          <div class="space-y-2 text-sm text-gray-700">
            <label class="flex cursor-pointer items-start gap-3 rounded border bg-white p-3">
              <input type="radio" name="payment_method" value="momo" <?= $old_payment_method === 'momo' ? 'checked' : '' ?>>
              <span>
                <span class="block font-medium text-gray-900">MoMo sandbox</span>
                <span class="block text-gray-500">Chuyển sang cổng MoMo test để thanh toán.</span>
              </span>
            </label>
            <label class="flex cursor-pointer items-start gap-3 rounded border bg-white p-3">
              <input type="radio" name="payment_method" value="vnpay" <?= $old_payment_method === 'vnpay' ? 'checked' : '' ?>>
              <span>
                <span class="block font-medium text-gray-900">VNPay sandbox</span>
                <span class="block text-gray-500">Chuyển sang cổng VNPay test để thanh toán.</span>
              </span>
            </label>
            <label class="flex cursor-pointer items-start gap-3 rounded border bg-white p-3">
              <input type="radio" name="payment_method" value="cod" <?= $old_payment_method === 'cod' ? 'checked' : '' ?>>
              <span>
                <span class="block font-medium text-gray-900">Thanh toán khi nhận hàng</span>
                <span class="block text-gray-500">Tạo đơn ngay, trạng thái thanh toán sẽ để chờ xử lý.</span>
              </span>
            </label>
          </div>
        </div>

        <button type="submit" class="w-full rounded-lg bg-fuchsia-700 px-4 py-3 font-medium text-white hover:bg-fuchsia-800">
          Tiếp tục thanh toán
        </button>
      </form>

      <div class="mt-3 text-center">
        <a href="<?= e(app_url('user/cart.php')) ?>" class="text-sm text-fuchsia-700 hover:underline">Quay lại giỏ hàng</a>
      </div>
    </div>

    <div class="rounded-xl border border-fuchsia-100 bg-fuchsia-50 p-5">
      <h2 class="text-base font-semibold text-fuchsia-900">Thông tin cổng test</h2>
      <div class="mt-4 space-y-3 text-sm text-fuchsia-900">
        <div class="rounded-lg bg-white p-3">
          <div class="text-xs uppercase text-fuchsia-600">MoMo partner code</div>
          <div class="mt-1 break-all font-medium"><?= e(MOMO_PARTNER_CODE) ?></div>
        </div>
        <div class="rounded-lg bg-white p-3">
          <div class="text-xs uppercase text-fuchsia-600">MoMo redirect URL</div>
          <div class="mt-1 break-all font-medium"><?= e(MOMO_REDIRECT_URL) ?></div>
        </div>
        <div class="rounded-lg bg-white p-3">
          <div class="text-xs uppercase text-fuchsia-600">VNPay return URL</div>
          <div class="mt-1 break-all font-medium"><?= e(VNPAY_RETURN_URL) ?></div>
        </div>
        <div class="rounded-lg bg-white p-3">
          <div class="text-xs uppercase text-fuchsia-600">VNPay IPN URL</div>
          <div class="mt-1 break-all font-medium"><?= e(VNPAY_IPN_URL) ?></div>
        </div>
        <div class="rounded-lg bg-white p-3">
          <div class="text-xs uppercase text-fuchsia-600">MoMo IPN URL</div>
          <div class="mt-1 break-all font-medium"><?= e(MOMO_IPN_URL) ?></div>
        </div>
      </div>
      <p class="mt-4 text-sm text-fuchsia-800">
        Nếu MoMo sandbox không gọi được `IPN URL` từ internet, đơn vẫn có thể được cập nhật khi người dùng quay lại `returnUrl`.
      </p>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

