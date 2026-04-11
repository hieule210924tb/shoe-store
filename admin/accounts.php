<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

$pageTitle = 'Admin - Quản lý tài khoản';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $uid = get_int('user_id', $_POST, 0);
  $role = get_str('role', $_POST);
  if ($uid > 0 && in_array($role, ['admin', 'user'], true)) {
    $self = current_user_id();
    if ($self && $uid === $self) {
      set_flash('error', 'Không thể đổi vai trò của chính bạn.');
    } else {
      $target = getOne('SELECT id, role FROM users WHERE id = ? LIMIT 1', [$uid]);
      if (!$target) {
        set_flash('error', 'Không tìm thấy tài khoản.');
      } elseif ($role === 'user' && ($target['role'] ?? '') === 'admin') {
        $adminCount = (int)(getOne('SELECT COUNT(*) AS c FROM users WHERE role = ?', ['admin'])['c'] ?? 0);
        if ($adminCount <= 1) {
          set_flash('error', 'Phải còn ít nhất một tài khoản quản trị.');
        } else {
          admin_set_user_role($uid, 'user');
          set_flash('success', 'Đã cập nhật vai trò.');
        }
      } else {
        admin_set_user_role($uid, $role);
        set_flash('success', 'Đã cập nhật vai trò.');
      }
    }
  }
  redirect('admin/accounts.php');
}

$q = get_str('q', $_GET);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$per_page = 15;

$total = admin_count_users($q !== '' ? $q : null);
$total_pages = (int)ceil($total / $per_page);
if ($total_pages > 0 && $page > $total_pages) {
  $page = $total_pages;
}
$offset = ($page - 1) * $per_page;

$users = admin_get_users($offset, $per_page, $q !== '' ? $q : null);

$stats = admin_stats();
$adminActive = 'accounts';
$adminHeading = 'Quản lý tài khoản';
$adminBreadcrumbs = [
  ['label' => 'Trang chủ', 'url' => route_url('admin', 'dashboard')],
  ['label' => 'Tài khoản', 'url' => null],
];
$adminNavBadge = (int)$stats['pending_orders'];

require_once __DIR__ . '/includes/layout_start.php';
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Danh sách người dùng</h3>
    <div class="card-tools">
      <span class="badge badge-secondary"><?= (int)$total ?> tài khoản</span>
    </div>
  </div>
  <div class="card-body border-bottom">
    <form method="get" class="form-inline">
      <input type="text" name="q" value="<?= e($q) ?>" class="form-control mr-2" placeholder="Tìm theo tên hoặc email">
      <button type="submit" class="btn btn-primary">Tìm</button>
    </form>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead>
        <tr>
          <th>ID</th>
          <th>Tên</th>
          <th>Email</th>
          <th>Vai trò</th>
          <th>Ngày tạo</th>
          <th style="width: 200px;">Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!$users): ?>
          <tr><td colspan="6" class="text-center text-muted py-4">Không có dữ liệu.</td></tr>
        <?php else: ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= (int)$u['id'] ?></td>
              <td><?= e($u['name']) ?></td>
              <td><?= e($u['email']) ?></td>
              <td>
                <?php if (($u['role'] ?? '') === 'admin'): ?>
                  <span class="badge badge-danger">Admin</span>
                <?php else: ?>
                  <span class="badge badge-info">Khách</span>
                <?php endif; ?>
              </td>
              <td><?= e((string)($u['created_at'] ?? '')) ?></td>
              <td>
                <?php if ((int)$u['id'] === (int)(current_user_id() ?? 0)): ?>
                  <span class="text-muted small">Tài khoản của bạn</span>
                <?php else: ?>
                  <form method="post" class="d-inline">
                    <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                    <?php if (($u['role'] ?? '') === 'admin'): ?>
                      <input type="hidden" name="role" value="user">
                      <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Đặt thành khách?');">Đặt làm khách</button>
                    <?php else: ?>
                      <input type="hidden" name="role" value="admin">
                      <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cấp quyền admin?');">Cấp admin</button>
                    <?php endif; ?>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($total_pages > 1): ?>
    <div class="card-footer clearfix">
      <ul class="pagination pagination-sm m-0 float-right">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= $i === $page ? 'active' : '' ?>">
            <a class="page-link" href="<?= e(app_url('admin/accounts.php?q=' . urlencode($q) . '&page=' . $i)) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/layout_end.php';
