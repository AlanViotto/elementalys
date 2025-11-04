<?php
/** @var array $suppliers */
require __DIR__ . '/../layout/header.php';
?>
<div class="row">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title">Cadastrar fornecedor</h5>
                <form method="post" action="index.php?page=suppliers">
                    <div class="mb-3">
                        <label class="form-label" for="supplier_name">Nome</label>
                        <input type="text" class="form-control" id="supplier_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="contact_name">Contato</label>
                        <input type="text" class="form-control" id="contact_name" name="contact_name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="supplier_email">E-mail</label>
                        <input type="email" class="form-control" id="supplier_email" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="supplier_phone">Telefone</label>
                        <input type="text" class="form-control" id="supplier_phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="supplier_notes">Anotações</label>
                        <textarea class="form-control" id="supplier_notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Fornecedores cadastrados</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Contato</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Anotações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($suppliers as $supplier): ?>
                            <tr>
                                <td><?= htmlspecialchars($supplier['name']) ?></td>
                                <td><?= htmlspecialchars($supplier['contact_name']) ?></td>
                                <td><?= htmlspecialchars($supplier['email']) ?></td>
                                <td><?= htmlspecialchars($supplier['phone']) ?></td>
                                <td><?= htmlspecialchars($supplier['notes']) ?></td>
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
