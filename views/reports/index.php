<?php
/** @var array $reportData */
/** @var DateTimeInterface $startDate */
/** @var DateTimeInterface $endDate */
/** @var string|null $rangeError */

$pageTitle = 'Relatórios';
$activeMenu = 'reports';
require __DIR__ . '/../layout/header.php';

$summary = $reportData['summary'];
$monthly = $reportData['monthly'];
$topProducts = $reportData['topProducts'];
$topCustomers = $reportData['topCustomers'];

$startValue = $startDate->format('Y-m-d');
$endValue = $endDate->format('Y-m-d');
?>
<div class="mb-4 text-muted">Personalize o período e acompanhe os indicadores financeiros das vendas para tomar decisões mais rápidas.</div>

<div class="card report-filter-card mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end" method="get" action="index.php">
            <input type="hidden" name="page" value="reports">
            <div class="col-sm-4 col-md-3">
                <label class="form-label" for="start">Data inicial</label>
                <input type="date" class="form-control" id="start" name="start" value="<?= htmlspecialchars($startValue) ?>">
            </div>
            <div class="col-sm-4 col-md-3">
                <label class="form-label" for="end">Data final</label>
                <input type="date" class="form-control" id="end" name="end" value="<?= htmlspecialchars($endValue) ?>">
            </div>
            <div class="col-sm-4 col-md-3">
                <button type="submit" class="btn btn-primary w-100">Atualizar período</button>
            </div>
            <div class="col-md-3 text-md-end">
                <span class="text-muted small">Período exibido: <?= $startDate->format('d/m/Y') ?> a <?= $endDate->format('d/m/Y') ?></span>
            </div>
        </form>
        <?php if (! empty($rangeError)): ?>
            <div class="alert alert-warning mt-3 mb-0">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($rangeError) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xxl-3">
        <div class="card report-summary-card">
            <div class="card-body">
                <span class="label">Receita total</span>
                <p class="value">R$ <?= number_format($summary['total_revenue'], 2, ',', '.') ?></p>
                <small class="text-muted">Soma das vendas no período</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xxl-3">
        <div class="card report-summary-card">
            <div class="card-body">
                <span class="label">Lucro estimado</span>
                <p class="value text-success">R$ <?= number_format($summary['total_profit'], 2, ',', '.') ?></p>
                <small class="text-muted">Receita menos custos registrados</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xxl-3">
        <div class="card report-summary-card">
            <div class="card-body">
                <span class="label">Vendas</span>
                <p class="value"><?= $summary['total_sales'] ?></p>
                <small class="text-muted">Pedidos concluídos</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xxl-3">
        <div class="card report-summary-card">
            <div class="card-body">
                <span class="label">Ticket médio</span>
                <p class="value">R$ <?= number_format($summary['average_ticket'], 2, ',', '.') ?></p>
                <small class="text-muted">Valor médio por venda</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xxl-8">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="card-title mb-1">Evolução mensal</h2>
                        <p class="text-muted small mb-0">Acompanhe receita, custos e lucro ao longo dos meses.</p>
                    </div>
                </div>
                <?php if (empty($monthly)): ?>
                    <p class="text-muted mb-0">Ainda não há vendas registradas no período selecionado.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Mês</th>
                                <th class="text-end">Receita</th>
                                <th class="text-end">Custo</th>
                                <th class="text-end">Lucro</th>
                                <th class="text-end">Itens</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($monthly as $month): ?>
                                <tr>
                                    <td><?= htmlspecialchars($month['period']) ?></td>
                                    <td class="text-end">R$ <?= number_format($month['revenue'], 2, ',', '.') ?></td>
                                    <td class="text-end">R$ <?= number_format($month['cost'], 2, ',', '.') ?></td>
                                    <td class="text-end <?= $month['profit'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                        R$ <?= number_format($month['profit'], 2, ',', '.') ?>
                                    </td>
                                    <td class="text-end"><?= $month['items'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xxl-4">
        <div class="card h-100 mb-4">
            <div class="card-body">
                <h2 class="card-title mb-3">Produtos em destaque</h2>
                <?php if (empty($topProducts)): ?>
                    <p class="text-muted mb-0">Nenhuma venda no período.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush report-ranking">
                        <?php foreach ($topProducts as $product): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><?= htmlspecialchars($product['name']) ?></strong>
                                    <div class="small text-muted">Lucro: R$ <?= number_format($product['total_profit'], 2, ',', '.') ?></div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary-subtle text-primary"><?= $product['total_quantity'] ?> un.</span>
                                    <div class="small text-muted">R$ <?= number_format($product['total_revenue'], 2, ',', '.') ?></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <div class="card h-100">
            <div class="card-body">
                <h2 class="card-title mb-3">Clientes recorrentes</h2>
                <?php if (empty($topCustomers)): ?>
                    <p class="text-muted mb-0">Nenhuma venda para clientes cadastrados no período.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush report-ranking">
                        <?php foreach ($topCustomers as $customer): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><?= htmlspecialchars($customer['name']) ?></strong>
                                    <div class="small text-muted">Pedidos: <?= $customer['orders'] ?></div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-secondary-subtle text-secondary">R$ <?= number_format($customer['total_revenue'], 2, ',', '.') ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php';
