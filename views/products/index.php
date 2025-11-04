<?php
/** @var array $products */
/** @var array $suppliers */
require __DIR__ . '/../layout/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Produtos</h1>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title">Cadastrar novo produto</h5>
                <form method="post" action="index.php?page=products">
                    <div class="mb-3">
                        <label class="form-label" for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="supplier_id">Fornecedor</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option value="">Selecione</option>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['id'] ?>"><?= htmlspecialchars($supplier['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="cost_price">Preço de custo (R$)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="cost_price" name="cost_price" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="markup_percentage">Markup (%)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="markup_percentage" name="markup_percentage" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="form-label" for="stock_quantity">Estoque</label>
                            <input type="number" min="0" class="form-control" id="stock_quantity" name="stock_quantity" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="min_stock_level">Estoque mínimo</label>
                            <input type="number" min="0" class="form-control" id="min_stock_level" name="min_stock_level" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Salvar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Lista de produtos</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Fornecedor</th>
                            <th>Preço custo</th>
                            <th>Preço venda</th>
                            <th>Estoque</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr data-stock="<?= $product['stock_quantity'] ?>" data-min="<?= $product['min_stock_level'] ?>">
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['supplier_name'] ?? '-') ?></td>
                                <td>R$ <?= number_format($product['cost_price'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($product['sale_price'], 2, ',', '.') ?></td>
                                <td><?= $product['stock_quantity'] ?> un.</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php';

