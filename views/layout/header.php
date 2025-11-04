<?php
$config = require __DIR__ . '/../../config/config.php';
$appName = $config['app']['name'];
$pageTitle = $pageTitle ?? 'Dashboard';
$activeMenu = $activeMenu ?? 'dashboard';

$menuItems = [
    'dashboard' => ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'href' => 'index.php?page=dashboard'],
    'products' => ['label' => 'Produtos', 'icon' => 'bi-box-seam', 'href' => 'index.php?page=products'],
    'sales' => ['label' => 'Vendas', 'icon' => 'bi-cart-check', 'href' => 'index.php?page=sales'],
    'customers' => ['label' => 'Clientes', 'icon' => 'bi-people', 'href' => 'index.php?page=customers'],
    'suppliers' => ['label' => 'Fornecedores', 'icon' => 'bi-truck', 'href' => 'index.php?page=suppliers'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName) ?> · <?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="app-body">
<div class="app-shell d-flex">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-cloud-fill"></i></div>
            <div>
                <span class="brand-subtitle">Painel de controle</span>
                <span class="brand-title"><?= htmlspecialchars($appName) ?></span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <?php foreach ($menuItems as $key => $item): ?>
                <a class="sidebar-link<?= $activeMenu === $key ? ' active' : '' ?>" href="<?= htmlspecialchars($item['href']) ?>">
                    <i class="bi <?= $item['icon'] ?>"></i>
                    <span><?= htmlspecialchars($item['label']) ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a class="sidebar-link" href="index.php?action=logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sair</span>
            </a>
        </div>
    </aside>
    <div class="app-main flex-grow-1 d-flex flex-column">
        <header class="topbar">
            <div>
                <div class="topbar-breadcrumb text-muted">Início / <?= htmlspecialchars($pageTitle) ?></div>
                <h1 class="topbar-title mb-0"><?= htmlspecialchars($pageTitle) ?></h1>
            </div>
            <div class="topbar-user d-flex align-items-center gap-3">
                <div class="user-greeting text-end">
                    <span class="d-block text-muted small">Bem-vindo de volta,</span>
                    <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuário') ?></strong>
                </div>
                <div class="user-avatar">
                    <span class="avatar-circle">
                        <i class="bi bi-person"></i>
                    </span>
                </div>
            </div>
        </header>
        <main class="app-content flex-grow-1">
