<?php

declare(strict_types=1);

$adminActive = $adminActive ?? 'dashboard';
$pageTitle = $pageTitle ?? 'Admin';
$adminName = $_SESSION['user']['name'] ?? 'Admin';
$adminBadge = isset($adminNavBadge) ? (int)$adminNavBadge : 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jqvmap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= e(route_url('user', 'index')) ?>" class="nav-link">Về cửa hàng</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <?php if ($adminBadge > 0): ?>
        <li class="nav-item dropdown">
          <a class="nav-link" href="<?= e(route_url('admin', 'orders')) ?>">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge"><?= $adminBadge > 99 ? 99 : $adminBadge ?></span>
          </a>
        </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="<?= e(app_url('auth/logout.php')) ?>" title="Đăng xuất">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= e(route_url('admin', 'dashboard')) ?>" class="brand-link">
      <span class="brand-text font-weight-light">ShoeStore Admin</span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <div class="img-circle elevation-2 bg-secondary d-flex align-items-center justify-content-center" style="width:2.1rem;height:2.1rem;">
            <i class="fas fa-user text-white"></i>
          </div>
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= e($adminName) ?></a>
        </div>
      </div>
      <div class="form-inline mb-3">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Tìm..." aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button>
          </div>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?= e(route_url('admin', 'dashboard')) ?>" class="nav-link <?= $adminActive === 'dashboard' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= e(route_url('admin', 'products')) ?>" class="nav-link <?= $adminActive === 'products' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-th"></i>
              <p>Quản lý sản phẩm</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= e(route_url('admin', 'accounts')) ?>" class="nav-link <?= $adminActive === 'accounts' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Quản lý tài khoản</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= e(route_url('admin', 'orders')) ?>" class="nav-link <?= $adminActive === 'orders' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Đơn hàng</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= e(route_url('admin', 'categories')) ?>" class="nav-link <?= $adminActive === 'categories' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tags"></i>
              <p>Danh mục</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?= e($adminHeading ?? 'Dashboard') ?></h1>
          </div>
          <div class="col-sm-6">
            <?php if (!empty($adminBreadcrumbs) && is_array($adminBreadcrumbs)): ?>
              <ol class="breadcrumb float-sm-right">
                <?php foreach ($adminBreadcrumbs as $i => $bc): ?>
                  <?php if (!empty($bc['url'])): ?>
                    <li class="breadcrumb-item"><a href="<?= e($bc['url']) ?>"><?= e($bc['label']) ?></a></li>
                  <?php else: ?>
                    <li class="breadcrumb-item active"><?= e($bc['label']) ?></li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ol>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <?php
        $flash = get_flash();
        if ($flash):
          $ft = $flash['type'] ?? 'info';
          $alertClass = $ft === 'error' ? 'danger' : ($ft === 'success' ? 'success' : 'info');
        ?>
          <div class="alert alert-<?= e($alertClass) ?> alert-dismissible fade show" role="alert">
            <?= e((string)($flash['message'] ?? '')) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
        <?php endif; ?>
