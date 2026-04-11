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

require_once __DIR__ . '/../includes/layout/header.php';
?>

<!-- Banner -->
<div class="rounded-xl bg-gradient-to-r from-blue-700 to-indigo-700 text-white p-6 md:p-10">
  <div class="max-w-4xl">
    <h1 class="text-2xl md:text-4xl font-bold">Giày đẹp - giá tốt - mua nhanh</h1>
    <p class="mt-2 text-white/90">Website demo bán giày cho người mới học PHP thuần.</p>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <?php foreach ($products as $p): ?>
        <div class="bg-white border rounded-lg overflow-hidden shadow-sm">
          <div class="h-44 bg-gray-100 flex items-center justify-center">
            <?php if (!empty($p['image_path'])): ?>
              <img src="<?= e(app_url($p['image_path'])) ?>" alt="<?= e($p['name']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <div class="text-gray-400 text-sm">No image</div>
            <?php endif; ?>
          </div>
          <div class="p-4">
            <div class="text-sm text-blue-700 font-medium"><?= e($p['category_name']) ?></div>
            <h3 class="mt-1 font-semibold"><?= e($p['name']) ?></h3>
            <div class="mt-2 text-gray-900 font-bold"><?= number_format((float)$p['price'], 0, ',', '.') ?> VND</div>
            <div class="mt-2 text-xs text-gray-600">
              Tồn kho: <?= (int)$p['stock_qty'] ?>
            </div>
            <div class="mt-3 flex flex-col gap-2">
              <a href="<?= e(app_url('user/product_detail.php?id=' . (int)$p['id'])) ?>" class="text-center px-3 py-2 rounded border hover:bg-gray-50">
                Xem chi tiết
              </a>
              <form method="POST" action="<?= e(app_url('user/cart_add.php')) ?>">
                <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                <button
                  class="w-full text-center px-3 py-2 rounded bg-blue-700 text-white hover:bg-blue-800 disabled:opacity-50"
                  type="submit"
                  <?= ((int)$p['stock_qty'] <= 0) ? 'disabled' : '' ?>
                >
                  Thêm vào giỏ
                </button>
              </form>
            </div>
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

