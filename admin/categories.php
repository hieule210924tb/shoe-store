<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

$pageTitle = 'Admin - Danh mục';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newName = get_str('category_name', $_POST);
  $deleteId = isset($_POST['delete_id']) ? (int)$_POST['delete_id'] : 0;

  if ($newName !== '') {
    try {
      admin_create_category($newName);
      set_flash('success', 'Đã thêm danh mục.');
    } catch (Throwable $e) {
      set_flash('error', 'Không thể thêm danh mục: ' . ($e->getMessage() ?: 'Lỗi không xác định'));
    }
    redirect('admin/categories.php');
  }

  if ($deleteId > 0) {
    try {
      admin_delete_category($deleteId);
      set_flash('success', 'Đã xoá danh mục.');
    } catch (Throwable $e) {
      set_flash('error', 'Không thể xoá danh mục (có thể đang có sản phẩm): ' . ($e->getMessage() ?: 'Lỗi không xác định'));
    }
    redirect('admin/categories.php');
  }
}

$categories = admin_get_categories();

$stats = admin_stats();
$adminActive = 'categories';
$adminHeading = 'Danh mục';
$adminBreadcrumbs = [
  ['label' => 'Trang chủ', 'url' => route_url('admin', 'dashboard')],
  ['label' => 'Danh mục', 'url' => null],
];
$adminNavBadge = (int)$stats['pending_orders'];

require_once __DIR__ . '/includes/layout_start.php';
?>

<div class="row mb-3">
  <div class="col text-right">
    <a href="<?= e(route_url('admin', 'products')) ?>" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-th"></i> Sản phẩm
    </a>
  </div>
</div>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">Thêm danh mục</h3>
  </div>
  <div class="card-body">
    <form method="post" class="form-row align-items-end">
      <div class="form-group col-md-8 col-lg-6 mb-0">
        <label for="category_name">Tên danh mục</label>
        <input
          id="category_name"
          type="text"
          name="category_name"
          class="form-control"
          placeholder="Ví dụ: Sneakers, Boot..."
          autocomplete="off"
        >
      </div>
      <div class="form-group col-md-4 col-lg-3 mb-0">
        <label class="d-none d-md-block">&nbsp;</label>
        <button type="submit" class="btn btn-primary btn-block">
          <i class="fas fa-plus"></i> Thêm
        </button>
      </div>
    </form>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header">
    <h3 class="card-title">Danh sách danh mục</h3>
    <div class="card-tools">
      <span class="badge badge-secondary"><?= count($categories) ?> mục</span>
    </div>
  </div>
  <div class="card-body p-0">
    <?php if (!$categories): ?>
      <p class="text-muted mb-0 p-3">Chưa có danh mục. Thêm danh mục phía trên trước khi tạo sản phẩm.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead>
            <tr>
              <th style="width: 90px;">ID</th>
              <th>Tên</th>
              <th style="width: 140px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categories as $c): ?>
              <tr>
                <td><?= (int)$c['id'] ?></td>
                <td class="font-weight-medium"><?= e($c['name']) ?></td>
                <td>
                  <form method="post" action="<?= e(app_url('admin/categories.php')) ?>" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá danh mục này?');">
                    <input type="hidden" name="delete_id" value="<?= (int)$c['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="fas fa-trash"></i> Xoá
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
