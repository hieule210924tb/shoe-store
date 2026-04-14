<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Trang chủ';

$categories = fetch_all_categories();

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$q = get_str('q', $_GET);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$per_page = 12;
$total = count_products($category_id ?: null, $q);
$total_pages = (int)ceil($total / $per_page);

if ($total_pages > 0 && $page > $total_pages) {
  $page = $total_pages;
}
$offset = ($page - 1) * $per_page;

$products = fetch_products($category_id ?: null, $q, $offset, $per_page);
$heroProducts = fetch_products(null, '', 0, 3);

require_once __DIR__ . '/../includes/layout/header.php';
?>

<!-- Banner -->
<div class="rounded-xl p-6 md:p-10" style="background:#d4ced4;">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
    <?php foreach ($heroProducts as $hero): ?>
      <div class="h-28 md:h-32 flex items-end justify-center">
        <?php if (!empty($hero['image_path'])): ?>
          <img
            src="<?= e(app_url($hero['image_path'])) ?>"
            alt="<?= e($hero['name']) ?>"
            class="h-full object-contain drop-shadow-sm"
          >
        <?php else: ?>
          <div class="text-gray-500 text-sm">Sneaker</div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="mt-8 text-center">
    <p class="text-gray-600 text-lg md:text-2xl">Mua giày chính hãng tại Sneaker Daily</p>
    <h1 class="mt-2 text-4xl md:text-6xl font-bold text-gray-800 tracking-tight">adidas x Sporty & Rich</h1>
    <a
      href="<?= e(app_url('user/index.php?page=1')) ?>"
      class="inline-block mt-5 px-7 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xl md:text-2xl"
    >
      MUA NGAY
    </a>
  </div>
</div>

<!-- Bộ lọc + Tìm kiếm -->
<div class="mt-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
  <div class="flex flex-wrap gap-2">
    <a href="<?= e(app_url('user/index.php')) ?>"
       class="px-3 py-2 rounded border <?= (!$category_id ? 'bg-blue-700 text-white border-blue-700' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50') ?>">
      Tất cả
    </a>
    <?php foreach ($categories as $c): ?>
      <?php $active = ((int)($category_id ?? 0) === (int)$c['id']); ?>
      <a href="<?= e(app_url('user/index.php?category_id=' . $c['id'] . '&q=' . urlencode($q) . '&page=1')) ?>"
         class="px-3 py-2 rounded border
           <?= $active ? 'bg-blue-700 text-white border-blue-700' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' ?>">
        <?= e($c['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <form method="GET" action="<?= e(app_url('user/index.php')) ?>" class="flex gap-2">
    <?php if ($category_id): ?>
      <input type="hidden" name="category_id" value="<?= (int)$category_id ?>">
    <?php endif; ?>
    <input
      name="q"
      value="<?= e($q) ?>"
      placeholder="Tìm theo tên giày..."
      class="w-full md:w-80 border border-gray-200 rounded px-3 py-2 bg-white"
    >
    <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-black" type="submit">Tìm</button>
  </form>
</div>

<!-- Danh sách sản phẩm -->
<div class="mt-6">
  <div class="flex items-center justify-between mb-3">
    <h2 class="font-semibold text-lg">Sản phẩm</h2>
    <div class="text-sm text-gray-600">
      <?= (int)$total ?> kết quả
      <?php if ($q !== ''): ?>
        cho "<?= e($q) ?>"
      <?php endif; ?>
    </div>
  </div>

  <?php if (!$products): ?>
    <div class="rounded border bg-white p-6 text-center text-gray-600">
      Không có sản phẩm phù hợp.
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <?php foreach ($products as $p): ?>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
          <div class="group relative h-44 bg-gray-50 flex items-center justify-center">
            <?php if (!empty($p['image_path'])): ?>
              <img src="<?= e(app_url($p['image_path'])) ?>" alt="<?= e($p['name']) ?>" class="w-full h-full object-contain p-3">
            <?php else: ?>
              <div class="text-gray-400 text-sm">No image</div>
            <?php endif; ?>

            <?php if ((int)$p['stock_qty'] > 0): ?>
              <form method="POST" action="<?= e(app_url('user/cart_add.php')) ?>" class="absolute right-3 bottom-3">
                <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                <input type="hidden" name="shoe_size" value="40">
                <button
                  class="w-10 h-10 rounded-full bg-white/95 border border-gray-200 text-gray-800 shadow-sm flex items-center justify-center opacity-0 translate-y-1 transition-all duration-200 group-hover:opacity-100 group-hover:translate-y-0 focus:opacity-100 focus:translate-y-0 hover:bg-red-500 hover:text-white"
                  type="submit"
                  aria-label="Thêm vào giỏ hàng"
                  title="Thêm vào giỏ hàng"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1"></circle>
                    <circle cx="18" cy="20" r="1"></circle>
                    <path d="M3 4h2l2.1 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 7H7"></path>
                  </svg>
                </button>
              </form>
            <?php else: ?>
              <span class="absolute right-3 bottom-3 text-xs px-2 py-1 rounded bg-gray-700 text-white">Hết hàng</span>
            <?php endif; ?>
          </div>
          <div class="p-3">
            <div class="text-sm text-gray-500"><?= e($p['category_name']) ?></div>
            <h3 class="mt-1 text-[15px] leading-5 font-medium text-gray-900 min-h-[2.5rem]">
              <a href="<?= e(app_url('user/product_detail.php?id=' . (int)$p['id'])) ?>" class="hover:text-blue-700">
                <?= e($p['name']) ?>
              </a>
            </h3>
            <div class="mt-2 text-xl font-bold text-gray-900"><?= number_format((float)$p['price'], 0, ',', '.') ?> đ</div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Phân trang -->
<?php if ($total_pages > 1): ?>
  <div class="mt-6 flex items-center justify-center gap-2">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <?php $active = ($i === $page); ?>
      <a
        href="<?= e(app_url('user/index.php?category_id=' . (int)($category_id ?? 0) . '&q=' . urlencode($q) . '&page=' . $i)) ?>"
        class="px-3 py-2 rounded border text-sm
          <?= $active ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' ?>"
      >
        <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

