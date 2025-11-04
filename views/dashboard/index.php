<?php
/** @var array $stats */
/** @var array $lowStockProducts */
/** @var array $lowStockSupplies */
$pageTitle = 'Dashboard';
$activeMenu = 'dashboard';
require __DIR__ . '/../layout/header.php';
?>
<div class="row g-4 mb-4">
    <div class="col-xxl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-boxes"></i></div>
                <span class="label">Produtos cadastrados</span>
                <p class="value mb-0"><?= $stats['products'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
                <span class="label">Receitas cadastradas</span>
                <p class="value mb-0"><?= $stats['recipes'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <span class="label">Clientes ativos</span>
                <p class="value mb-0"><?= $stats['customers'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-truck"></i></div>
                <span class="label">Fornecedores</span>
                <p class="value mb-0"><?= $stats['suppliers'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-archive"></i></div>
                <span class="label">Insumos cadastrados</span>
                <p class="value mb-0"><?= $stats['supplies'] ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xxl-8">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="card-title mb-1">Receita acumulada</h2>
                        <p class="text-muted mb-0">Valor total considerando todas as vendas registradas.</p>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary">Atualizado</span>
                </div>
                <p class="display-5 fw-bold text-primary">R$ <?= number_format($stats['revenue'], 2, ',', '.') ?></p>
                <div class="quick-actions mt-4">
                    <h2>Atalhos rápidos</h2>
                    <div class="quick-action-grid">
                        <a class="quick-action-card" href="index.php?page=products">
                            <span class="icon"><i class="bi bi-box-seam"></i></span>
                            <span>Produtos</span>
                            <small>Cadastro e controle de itens</small>
                        </a>
                        <a class="quick-action-card" href="index.php?page=sales">
                            <span class="icon"><i class="bi bi-arrow-up-right-circle"></i></span>
                            <span>Saídas (vendas)</span>
                            <small><?= $stats['sales'] ?> vendas registradas</small>
                        </a>
                        <a class="quick-action-card" href="index.php?page=customers">
                            <span class="icon"><i class="bi bi-people"></i></span>
                            <span>Clientes</span>
                            <small>Gerencie sua carteira</small>
                        </a>
                        <a class="quick-action-card" href="index.php?page=suppliers">
                            <span class="icon"><i class="bi bi-truck"></i></span>
                            <span>Fornecedores</span>
                            <small>Cadastre parceiros de compra</small>
                        </a>
                        <a class="quick-action-card" href="index.php?page=supplies">
                            <span class="icon"><i class="bi bi-archive"></i></span>
                            <span>Insumos</span>
                            <small>Controle entradas e saídas</small>
                        </a>
                        <a class="quick-action-card" href="index.php?page=recipes">
                            <span class="icon"><i class="bi bi-journal-richtext"></i></span>
                            <span>Receitas</span>
                            <small>Organize preparo e insumos</small>
                        </a>
                        <a class="quick-action-card" href="index.php?page=products#low-stock-card">
                            <span class="icon"><i class="bi bi-exclamation-circle"></i></span>
                            <span>Alertas</span>
                            <small>Acompanhe o estoque crítico</small>
                        </a>
                        <a class="quick-action-card disabled" href="#" aria-disabled="true">
                            <span class="icon"><i class="bi bi-file-earmark-bar-graph"></i></span>
                            <span>Relatórios</span>
                            <small>Relatórios personalizados em breve</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-4">
        <div class="card h-100" id="low-stock-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="card-title mb-0">Alertas de estoque</h2>
                    <span class="badge bg-danger bg-opacity-10 text-danger"><i class="bi bi-bell"></i> atenção</span>
                </div>
                <?php $hasLowStock = ! empty($lowStockProducts) || ! empty($lowStockSupplies); ?>
                <?php if (! $hasLowStock): ?>
                    <p class="text-muted mb-0">Nenhum produto ou insumo está abaixo do mínimo.</p>
                <?php else: ?>
                    <?php if (! empty($lowStockProducts)): ?>
                        <h6 class="text-uppercase text-muted small mt-0">Produtos</h6>
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach ($lowStockProducts as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold"><?= htmlspecialchars($item['name']) ?></span>
                                    <span class="badge bg-danger"><?= $item['stock_quantity'] ?> un.</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if (! empty($lowStockSupplies)): ?>
                        <h6 class="text-uppercase text-muted small">Insumos</h6>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($lowStockSupplies as $supply): ?>
                                <?php $unit = $supply['unit'] ? $supply['unit'] : 'un.'; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold"><?= htmlspecialchars($supply['name']) ?></span>
                                    <span class="badge bg-warning text-dark"><?= $supply['stock_quantity'] ?> <?= htmlspecialchars($unit) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php';
