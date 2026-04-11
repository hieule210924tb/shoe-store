<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();


$pageTitle = 'Admin - Sản phẩm';

$q = get_str('q', $_GET);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$per_page = 10;

$total = admin_count_products($q);
$total_pages = (int)ceil($total / $per_page);
if ($total_pages > 0 && $page > $total_pages) {
  $page = $total_pages;
}
$offset = ($page - 1) * $per_page;

$products = admin_get_products($offset, $per_page, $q);

$stats = admin_stats();
$adminActive = 'products';
$adminHeading = 'Quản lý sản phẩm';
$adminBreadcrumbs = [
  ['label' => 'Trang chủ', 'url' => route_url('admin', 'dashboard')],
  ['label' => 'Sản phẩm', 'url' => null],
];
$adminNavBadge = (int)$stats['pending_orders'];

require_once __DIR__ . '/includes/layout_start.php';
?>

<div class="row mb-3">
  <div class="col text-right">
    <a href="<?= e(app_url('admin/product_add.php')) ?>" class="btn btn-primary">
      <i class="fas fa-plus"></i> Thêm sản phẩm
    </a>
  </div>
</div>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">Tìm kiếm</h3>
  </div>
  <div class="card-body">
    <form method="get" class="form-row align-items-end">
      <div class="form-group col-md-8 col-lg-6 mb-0">
        <label for="product-q">Tìm theo tên</label>
        <input
          id="product-q"
          type="text"
          name="q"
          value="<?= e($q) ?>"
          class="form-control"
          placeholder="Ví dụ: Air, Boot..."
        >
      </div>
      <div class="form-group col-md-4 col-lg-3 mb-0">
        <label class="d-none d-md-block">&nbsp;</label>
        <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-striped mb-0">
      <thead>
        <tr>
          <th style="width: 90px;">Hình</th>
          <th>Tên</th>
          <th>Danh mục</th>
          <th>Giá</th>
          <th>Tồn</th>
          <th style="width: 200px;">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$products): ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-4">Không có sản phẩm.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($products as $p): ?>
            <tr>
              <td>
                <div class="border rounded overflow-hidden bg-light d-flex align-items-center justify-content-center" style="width:56px;height:40px;">
                  <?php if (!empty($p['image_path'])): ?>
                    <img src="<?= e(app_url($p['image_path'])) ?>" alt="<?= e($p['name']) ?>" class="img-fluid" style="max-height:40px;object-fit:cover;width:100%;">
                  <?php endif; ?>
                </div>
              </td>
              <td class="font-weight-medium"><?= e($p['name']) ?></td>
              <td><?= e($p['category_name']) ?></td>
              <td class="font-weight-medium"><?= number_format((float)$p['price'], 0, ',', '.') ?> VND</td>
              <td><?= (int)$p['stock_qty'] ?></td>
              <td>
                <a href="<?= e(app_url('admin/product_edit.php?id=' . (int)$p['id'])) ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                <form method="POST" action="<?= e(app_url('admin/product_delete.php')) ?>" class="d-inline" onsubmit="return confirm('Xoá sản phẩm này?');">
                  <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                  <button class="btn btn-sm btn-danger" type="submit">Xoá</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($total_pages > 1): ?>
  <nav class="mt-3" aria-label="Phân trang">
    <ul class="pagination justify-content-center mb-0">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <?php $active = ($i === $page); ?>
        <li class="page-item <?= $active ? 'active' : '' ?>">
          <a class="page-link" href="<?= e(app_url('admin/products.php?q=' . urlencode($q) . '&page=' . $i)) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
