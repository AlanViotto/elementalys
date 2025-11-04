<?php
/** @var array $supplies */
/** @var array $suppliers */
/** @var array|null $supplyFeedback */
/** @var array|null $stockFeedback */

$pageTitle = 'Insumos';
$activeMenu = 'supplies';
require __DIR__ . '/../layout/header.php';
?>
<div class="mb-4 text-muted">Gerencie o estoque de ceras, essências e demais insumos para garantir a produção das receitas.</div>
<div class="row g-4">
    <div class="col-xxl-8 d-flex">
        <div class="card h-100 me-4">
            <div class="card-body">
                <h5 class="card-title">Cadastrar novo insumo</h5>
                <p class="text-muted small">Registre unidades de medida, fornecedor e estoque mínimo para acompanhar alertas.</p>
                <?php if ($supplyFeedback): ?>
                    <div class="alert alert-<?= $supplyFeedback['success'] ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($supplyFeedback['message']) ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="index.php?page=supplies" class="form-styled">
                    <input type="hidden" name="form" value="supply">
                    <div class="mb-3">
                        <label class="form-label" for="supply_name">Nome do insumo</label>
                        <input type="text" class="form-control" id="supply_name" name="name" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="supply_unit">Unidade de medida</label>
                            <input type="text" class="form-control" id="supply_unit" name="unit" placeholder="Ex: kg, L, un.">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="supply_supplier">Fornecedor</label>
                            <select class="form-select" id="supply_supplier" name="supplier_id">
                                <option value="">Sem fornecedor</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>"><?= htmlspecialchars($supplier['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-sm-4">
                            <label class="form-label" for="supply_stock">Estoque inicial</label>
                            <input type="number" min="0" class="form-control" id="supply_stock" name="stock_quantity" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="supply_min">Estoque mínimo</label>
                            <input type="number" min="0" class="form-control" id="supply_min" name="min_stock_level" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="supply_cost">Custo unitário (R$)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="supply_cost" name="cost_per_unit">
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label" for="supply_notes">Observações</label>
                        <textarea class="form-control" id="supply_notes" name="notes" rows="2" placeholder="Informações adicionais sobre o insumo."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar insumo</button>
                </form>
            </div>
        </div>
        <div class="card p-4">
            <div class="card-body">
                <h6 class="card-title">Movimentar estoque</h6>
                <p class="text-muted small mb-3">Atualize entradas de compra ou saídas utilizadas nas receitas.</p>
                <?php if ($stockFeedback): ?>
                    <div class="alert alert-<?= $stockFeedback['success'] ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($stockFeedback['message']) ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="index.php?page=supplies" class="form-styled">
                    <input type="hidden" name="form" value="stock">
                    <div class="mb-3">
                        <label class="form-label" for="movement_supply_id">Insumo</label>
                        <select class="form-select" id="movement_supply_id" name="supply_id" required>
                            <option value="">Selecione</option>
                            <?php foreach ($supplies as $supply): ?>
                                <option value="<?= $supply['id'] ?>"><?= htmlspecialchars($supply['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="movement_type">Tipo de movimentação</label>
                        <select class="form-select" id="movement_type" name="movement">
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saída</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="movement_quantity">Quantidade</label>
                        <input type="number" min="1" class="form-control" id="movement_quantity" name="quantity" required>
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100">Registrar movimentação</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xxl-4">
        <div class="category-board">
            <?php foreach ($supplies as $supply): ?>
                <?php
                    $current = (int) $supply['stock_quantity'];
                    $minimum = (int) $supply['min_stock_level'];
                    $isLow = $current <= $minimum;
                ?>
                <section class="card category-card<?= $isLow ? ' border-danger' : '' ?>" id="supply-<?= $supply['id'] ?>">
                    <div class="card-body">
                        <div class="category-header">
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($supply['name']) ?></h5>
                                <?php if (! empty($supply['unit'])): ?>
                                    <p class="text-muted small mb-0">Unidade: <?= htmlspecialchars($supply['unit']) ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="badge rounded-pill <?= $isLow ? 'bg-danger text-white' : 'bg-success-subtle text-success' ?>">
                                <?= $current ?> <?= htmlspecialchars($supply['unit'] ?: 'un.') ?>
                            </span>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-light text-primary">Estoque mínimo: <?= $minimum ?> <?= htmlspecialchars($supply['unit'] ?: 'un.') ?></span>
                            <?php if (! empty($supply['cost_per_unit'])): ?>
                                <span class="badge bg-light text-muted">Custo: R$ <?= number_format((float) $supply['cost_per_unit'], 2, ',', '.') ?> / <?= htmlspecialchars($supply['unit'] ?: 'un.') ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (! empty($supply['supplier_name'])): ?>
                            <p class="text-muted small mb-1"><i class="bi bi-truck"></i> <?= htmlspecialchars($supply['supplier_name']) ?></p>
                        <?php endif; ?>
                        <?php if (! empty($supply['notes'])): ?>
                            <p class="small text-body-secondary mb-0"><?= nl2br(htmlspecialchars($supply['notes'])) ?></p>
                        <?php else: ?>
                            <p class="text-muted small mb-0">Nenhuma observação cadastrada.</p>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endforeach; ?>
            <?php if (empty($supplies)): ?>
                <section class="card category-card">
                    <div class="card-body text-center text-muted">
                        <i class="bi bi-archive mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">Cadastre seus primeiros insumos para acompanhar o estoque.</p>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php';
