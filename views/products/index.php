<?php
/** @var array $productGroups */
/** @var array $productCategories */
/** @var array $suppliers */
/** @var array $recipes */
/** @var array|null $productFeedback */
/** @var array|null $categoryFeedback */
$pageTitle = 'Produtos';
$activeMenu = 'products';
require __DIR__ . '/../layout/header.php';
?>
<div class="mb-4 text-muted">Mantenha seu catálogo atualizado, destacando fotos, categorias e vínculos com as receitas artesanais.</div>
<div class="row g-4">
    <div class="col-xxl-8 d-flex">
        <div class="card h-100 me-4">
            <div class="card-body">
                <h5 class="card-title">Cadastrar novo produto</h5>
                <p class="text-muted small">Preencha as informações para que o sistema calcule automaticamente o preço de venda.</p>
                <?php if ($productFeedback): ?>
                    <div class="alert alert-<?= $productFeedback['success'] ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($productFeedback['message']) ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="index.php?page=products" class="form-styled">
                    <input type="hidden" name="form" value="product">
                    <div class="mb-3">
                        <label class="form-label" for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="product_category_id">Categoria</label>
                        <select class="form-select" id="product_category_id" name="product_category_id" required>
                            <option value="">Selecione uma categoria</option>
                            <?php foreach ($productCategories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="product_type">Tipo de produto</label>
                        <select class="form-select product-type-select" id="product_type" name="product_type">
                            <option value="Pronto">Pronto</option>
                            <option value="Artesanal">Artesanal</option>
                        </select>
                        <small class="text-muted">Produtos artesanais exigem vincular a receita correspondente.</small>
                    </div>
                    <div class="mb-3 conditional-field" data-type="Artesanal">
                        <label class="form-label" for="recipe_id">Receita relacionada</label>
                        <select class="form-select" id="recipe_id" name="recipe_id">
                            <option value="">Selecione a receita</option>
                            <?php foreach ($recipes as $recipe): ?>
                                <option value="<?= $recipe['id'] ?>"><?= htmlspecialchars($recipe['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="image_path">Foto do produto (URL)</label>
                        <input type="url" class="form-control" id="image_path" name="image_path" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Notas sobre fragrâncias, materiais e cuidados."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="supplier_id">Fornecedor</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option value="">Sem fornecedor</option>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['id'] ?>"><?= htmlspecialchars($supplier['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="cost_price">Preço de custo (R$)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="cost_price" name="cost_price" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="markup_percentage">Markup (%)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="markup_percentage" name="markup_percentage" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-sm-6">
                            <label class="form-label" for="stock_quantity">Estoque atual</label>
                            <input type="number" min="0" class="form-control" id="stock_quantity" name="stock_quantity" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="min_stock_level">Estoque mínimo</label>
                            <input type="number" min="0" class="form-control" id="min_stock_level" name="min_stock_level" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Salvar produto</button>
                </form>
            </div>
        </div>
        <div class="card p-4">
            <div class="card-body">
                <h6 class="card-title">Criar categoria</h6>
                <p class="text-muted small mb-3">Organize a vitrine em coleções por aroma, uso ou ocasião.</p>
                <?php if ($categoryFeedback): ?>
                    <div class="alert alert-<?= $categoryFeedback['success'] ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($categoryFeedback['message']) ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="index.php?page=products" class="form-styled">
                    <input type="hidden" name="form" value="category">
                    <div class="mb-3">
                        <label class="form-label" for="category_name">Nome da categoria</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="category_description">Descrição</label>
                        <textarea class="form-control" id="category_description" name="description" rows="2" placeholder="Ex: Linha relaxante com lavanda e camomila."></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100">Salvar categoria</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xxl-4">
        <div class="category-board">
            <?php foreach ($productGroups as $group): ?>
                <section class="card category-card" id="category-<?= $group['id'] ?>">
                    <div class="card-body">
                        <div class="category-header">
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($group['name']) ?></h5>
                                <?php if (! empty($group['description'])): ?>
                                    <p class="text-muted small mb-0"><?= htmlspecialchars($group['description']) ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="badge rounded-pill bg-primary-subtle text-primary"><?= count($group['products']) ?> produto(s)</span>
                        </div>
                        <?php if (empty($group['products'])): ?>
                            <p class="text-muted mb-0">Ainda não há produtos nessa categoria.</p>
                        <?php else: ?>
                            <div class="product-grid">
                                <?php foreach ($group['products'] as $product): ?>
                                    <article class="product-card" data-stock="<?= $product['stock_quantity'] ?>" data-min="<?= $product['min_stock_level'] ?>">
                                        <div class="product-media">
                                            <?php if (! empty($product['image_path'])): ?>
                                                <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                                            <?php else: ?>
                                                <div class="product-placeholder"><i class="bi bi-image"></i></div>
                                            <?php endif; ?>
                                            <span class="badge product-type-badge <?= $product['product_type'] === 'Artesanal' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' ?>"><?= htmlspecialchars($product['product_type']) ?></span>
                                        </div>
                                        <div class="product-info">
                                            <h6 class="product-name mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                                            <?php if (! empty($product['description'])): ?>
                                                <p class="text-muted small mb-2"><?= htmlspecialchars($product['description']) ?></p>
                                            <?php endif; ?>
                                            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                                <span class="price-chip">Custo: R$ <?= number_format($product['cost_price'], 2, ',', '.') ?></span>
                                                <span class="price-chip">Venda: R$ <?= number_format($product['sale_price'], 2, ',', '.') ?></span>
                                            </div>
                                            <div class="product-meta">
                                                <span class="badge bg-light text-primary">Estoque: <?= (int) $product['stock_quantity'] ?> un.</span>
                                                <span class="badge bg-light text-muted">Mínimo: <?= (int) $product['min_stock_level'] ?> un.</span>
                                            </div>
                                            <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                                                <?php if (! empty($product['supplier_name'])): ?>
                                                    <span class="text-muted small"><i class="bi bi-truck"></i> <?= htmlspecialchars($product['supplier_name']) ?></span>
                                                <?php endif; ?>
                                                <?php if ($product['product_type'] === 'Artesanal' && ! empty($product['recipe_id'])): ?>
                                                    <a class="text-decoration-none small" href="index.php?page=recipes#recipe-<?= $product['recipe_id'] ?>">
                                                        <i class="bi bi-journal-text"></i> Ver receita
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php';
