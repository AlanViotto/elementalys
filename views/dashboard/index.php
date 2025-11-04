<?php
/** @var array $stats */
/** @var array $lowStock */
require __DIR__ . '/../layout/header.php';
?>
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Produtos</h6>
                <p class="display-6 mb-0"><?= $stats['products'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Clientes</h6>
                <p class="display-6 mb-0"><?= $stats['customers'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Fornecedores</h6>
                <p class="display-6 mb-0"><?= $stats['suppliers'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Vendas</h6>
                <p class="display-6 mb-0"><?= $stats['sales'] ?></p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title">Receita acumulada</h5>
                <p class="display-6">R$ <?= number_format($stats['revenue'], 2, ',', '.') ?></p>
                <p class="text-muted mb-0">Baseada no valor total das vendas realizadas.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0" id="low-stock-card">
            <div class="card-body">
                <h5 class="card-title">Alertas de estoque baixo</h5>
                <?php if (empty($lowStock)): ?>
                    <p class="text-muted mb-0">Todos os produtos estão com estoque saudável.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($lowStock as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?= htmlspecialchars($item['name']) ?></span>
                                <span class="badge bg-danger"><?= $item['stock_quantity'] ?> un.</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php';
