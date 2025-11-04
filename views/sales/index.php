<?php
/** @var array $sales */
/** @var array $products */
/** @var array $customers */
$pageTitle = 'Vendas';
$activeMenu = 'sales';
require __DIR__ . '/../layout/header.php';
?>
<div class="mb-4 text-muted">Registre cada saída de estoque e acompanhe o desempenho das vendas.</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Registrar venda</h5>
                <form method="post" action="index.php?page=sales">
                    <div class="mb-3">
                        <label class="form-label" for="product_id">Produto</label>
                        <select class="form-select" id="product_id" name="product_id" required>
                            <option value="">Selecione</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="customer_id">Cliente</label>
                        <select class="form-select" id="customer_id" name="customer_id">
                            <option value="">Consumidor final</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id'] ?>"><?= htmlspecialchars($customer['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="quantity">Quantidade</label>
                        <input type="number" min="1" class="form-control" id="quantity" name="quantity" value="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Histórico de vendas</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Data</th>
                            <th>Produto</th>
                            <th>Cliente</th>
                            <th>Quantidade</th>
                            <th>Total custo</th>
                            <th>Total venda</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($sales as $sale): ?>
                            <tr>
                                <td><?= (new DateTime($sale['created_at']))->format('d/m/Y H:i') ?></td>
                                <td><?= htmlspecialchars($sale['product_name']) ?></td>
                                <td><?= htmlspecialchars($sale['customer_name'] ?? 'Consumidor final') ?></td>
                                <td><?= $sale['quantity'] ?></td>
                                <td>R$ <?= number_format($sale['total_cost'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($sale['total_price'], 2, ',', '.') ?></td>
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
