<?php
/** @var array $customers */
$pageTitle = 'Clientes';
$activeMenu = 'customers';
require __DIR__ . '/../layout/header.php';
?>
<div class="mb-4 text-muted">Cadastre clientes para acompanhar histórico de compras e contatos.</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Cadastrar cliente</h5>
                <form method="post" action="index.php?page=customers">
                    <div class="mb-3">
                        <label class="form-label" for="customer_name">Nome</label>
                        <input type="text" class="form-control" id="customer_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="customer_email">E-mail</label>
                        <input type="email" class="form-control" id="customer_email" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="customer_phone">Telefone</label>
                        <input type="text" class="form-control" id="customer_phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="customer_address">Endereço</label>
                        <textarea class="form-control" id="customer_address" name="address" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Clientes cadastrados</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Endereço</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?= htmlspecialchars($customer['name']) ?></td>
                                <td><?= htmlspecialchars($customer['email']) ?></td>
                                <td><?= htmlspecialchars($customer['phone']) ?></td>
                                <td><?= htmlspecialchars($customer['address']) ?></td>
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
