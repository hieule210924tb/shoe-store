<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$uid = current_user_id();
if (!$uid) {
  set_flash('error', 'Vui lòng đăng nhập.');
  redirect('auth/login.php');
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$product_id = $method === 'POST'
  ? (isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0)
  : (isset($_GET['id']) ? (int)$_GET['id'] : 0);

if ($product_id <= 0) {
  set_flash('error', 'Sản phẩm không hợp lệ.');
  redirect('user/order_history.php');
}

$product = fetch_product_with_category($product_id);
if (!$product) {
  set_flash('error', 'Sản phẩm không tồn tại.');
  redirect('user/index.php');
}

if (!user_has_paid_product($uid, $product_id)) {
  set_flash('error', 'Bạn chỉ có thể đánh giá sản phẩm đã mua và thanh toán thành công.');
  redirect('user/order_history.php');
}

if ($method === 'POST') {
  $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
  $comment = isset($_POST['comment']) ? trim((string)$_POST['comment']) : '';

  if ($rating < 1 || $rating > 5) {
    set_flash('error', 'Đánh giá sao không hợp lệ.');
    redirect('user/product_rate.php?id=' . (int)$product_id);
  }

  // Giới hạn độ dài comment để tránh payload quá lớn.
  $comment = $comment === '' ? null : $comment;
  if ($comment !== null && mb_strlen($comment) > 1000) {
    set_flash('error', 'Nội dung bình luận quá dài (tối đa 1000 ký tự).');
    redirect('user/product_rate.php?id=' . (int)$product_id);
  }

  upsert_product_review($product_id, $uid, $rating, $comment);

  set_flash('success', 'Đã lưu đánh giá. Cảm ơn bạn!');
  redirect('/');
}

$pageTitle = 'Đánh giá sản phẩm';
$myReview = fetch_user_product_review($product_id, $uid);
$myRating = (int)($myReview['rating'] ?? 0);

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="max-w-2xl mx-auto">
  <div class="flex items-center justify-between gap-4">
    <h1 class="text-2xl font-bold">Đánh giá sản phẩm</h1>
    <a href="<?= e(app_url('user/order_history.php')) ?>" class="text-sm text-blue-700 hover:underline">← Quay lại đơn đã mua</a>
  </div>

  <div class="mt-6 bg-white border rounded-lg p-5">
    <div class="flex gap-4 items-start">
      <div class="w-24 h-24 bg-gray-100 rounded overflow-hidden flex items-center justify-center shrink-0">
        <?php if (!empty($product['image_path'])): ?>
          <img src="<?= e(app_url($product['image_path'])) ?>" alt="<?= e($product['name']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
          <div class="text-gray-400 text-xs">No image</div>
        <?php endif; ?>
      </div>
      <div>
        <div class="text-sm text-blue-700 font-medium"><?= e($product['category_name'] ?? '') ?></div>
        <div class="text-lg font-semibold mt-1"><?= e($product['name']) ?></div>
        <div class="text-sm text-gray-600 mt-1">
          <?= number_format((float)$product['price'], 0, ',', '.') ?> VND
        </div>
      </div>
    </div>

    <form method="POST" action="<?= e(app_url('user/product_rate.php')) ?>" class="space-y-4 mt-6 border-t pt-5">
      <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

      <div>
        <div class="text-sm text-gray-700 mb-1">Đánh giá sao</div>
        <div class="flex items-center gap-2" id="rating-stars">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <label for="rating-<?= $i ?>" class="cursor-pointer">
              <input
                id="rating-<?= $i ?>"
                type="radio"
                name="rating"
                value="<?= $i ?>"
                class="sr-only"
                required
                <?= ($myRating === $i) ? 'checked' : '' ?>
              >
              <span
                data-rating="<?= $i ?>"
                class="rating-star text-3xl leading-none select-none <?= ($myRating >= $i && $myRating > 0) ? 'text-yellow-500' : 'text-gray-300' ?>"
              >
                &#9733;
              </span>
            </label>
          <?php endfor; ?>
        </div>
      </div>

      <div>
        <label class="text-sm text-gray-700 mb-1 block">Bình luận</label>
        <textarea
          name="comment"
          rows="4"
          class="w-full border border-gray-200 rounded px-3 py-2 bg-white"
          placeholder="Viết nhận xét của bạn..."
        ><?= e($myReview['comment'] ?? '') ?></textarea>
      </div>

      <button
        type="submit"
        class="px-4 py-2 rounded bg-blue-700 text-white hover:bg-blue-800"
      >
        Gửi đánh giá
      </button>
    </form>
  </div>
</div>

<script>
  (function () {
    const starsWrap = document.getElementById('rating-stars');
    if (!starsWrap) return;

    const stars = Array.from(starsWrap.querySelectorAll('.rating-star'));
    const radios = Array.from(starsWrap.querySelectorAll('input[name="rating"]'));

    function paint(activeRating) {
      stars.forEach((star) => {
        const value = Number(star.dataset.rating || 0);
        star.classList.toggle('text-yellow-500', value <= activeRating);
        star.classList.toggle('text-gray-300', value > activeRating);
      });
    }

    const checked = radios.find((radio) => radio.checked);
    paint(checked ? Number(checked.value) : 0);

    stars.forEach((star) => {
      const value = Number(star.dataset.rating || 0);
      star.addEventListener('click', function () {
        const radio = starsWrap.querySelector('#rating-' + value);
        if (!radio) return;
        radio.checked = true;
        paint(value);
      });

      star.addEventListener('mouseenter', function () {
        paint(value);
      });
    });

    starsWrap.addEventListener('mouseleave', function () {
      const selected = radios.find((radio) => radio.checked);
      paint(selected ? Number(selected.value) : 0);
    });
  })();
</script>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

