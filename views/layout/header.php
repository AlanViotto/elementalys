<?php
$config = require __DIR__ . '/../../config/config.php';
$appName = $config['app']['name'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Elementalys</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=products">Produtos</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=customers">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=suppliers">Fornecedores</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=sales">Vendas</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Olá, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuário') ?></span>
                <a class="btn btn-outline-light" href="index.php?action=logout">Sair</a>
            </div>
        </div>
    </div>
</nav>
<main class="container py-4">
