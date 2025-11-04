<?php
/** @var array $recipeGroups */
/** @var array $recipeCategories */
/** @var array|null $recipeFeedback */
/** @var array|null $recipeCategoryFeedback */
$pageTitle = 'Receitas';
$activeMenu = 'recipes';
require __DIR__ . '/../layout/header.php';
?>
<div class="mb-4 text-muted">Cadastre e organize o passo a passo das suas criações artesanais para reutilizar insumos com precisão.</div>
<div class="row g-4">
    <div class="col-xxl-8 d-flex">
        <div class="card h-100 me-4">
            <div class="card-body">
                <h5 class="card-title">Cadastrar nova receita</h5>
                <p class="text-muted small">Descreva ingredientes, rendimento e tempo de preparo para orientar a produção.</p>
                <?php if ($recipeFeedback): ?>
                    <div class="alert alert-<?= $recipeFeedback['success'] ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($recipeFeedback['message']) ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="index.php?page=recipes" class="form-styled">
                    <input type="hidden" name="form" value="recipe">
                    <div class="mb-3">
                        <label class="form-label" for="recipe_name">Nome</label>
                        <input type="text" class="form-control" id="recipe_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="recipe_category_id">Categoria</label>
                        <select class="form-select" id="recipe_category_id" name="recipe_category_id">
                            <option value="">Sem categoria</option>
                            <?php foreach ($recipeCategories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="recipe_summary">Resumo</label>
                        <textarea class="form-control" id="recipe_summary" name="summary" rows="2" placeholder="Breve descrição da finalidade da receita."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="recipe_ingredients">Ingredientes</label>
                        <textarea class="form-control" id="recipe_ingredients" name="ingredients" rows="3" placeholder="Liste insumos com quantidades."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="recipe_instructions">Modo de preparo</label>
                        <textarea class="form-control" id="recipe_instructions" name="instructions" rows="4" required placeholder="Explique as etapas do processo."></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="recipe_preparation_time">Tempo de preparo</label>
                            <input type="text" class="form-control" id="recipe_preparation_time" name="preparation_time" placeholder="Ex: 45 minutos">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="recipe_yield">Rendimento</label>
                            <input type="text" class="form-control" id="recipe_yield" name="yield_description" placeholder="Ex: 12 velas">
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label" for="recipe_image">Foto ilustrativa (URL)</label>
                        <input type="url" class="form-control" id="recipe_image" name="image_path" placeholder="https://...">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar receita</button>
                </form>
            </div>
        </div>
        <div class="card p-4">
            <div class="card-body">
                <h6 class="card-title">Criar categoria</h6>
                <p class="text-muted small mb-3">Separe por família olfativa, linha temática ou finalidade.</p>
                <?php if ($recipeCategoryFeedback): ?>
                    <div class="alert alert-<?= $recipeCategoryFeedback['success'] ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($recipeCategoryFeedback['message']) ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="index.php?page=recipes" class="form-styled">
                    <input type="hidden" name="form" value="category">
                    <div class="mb-3">
                        <label class="form-label" for="recipe_category_name">Nome da categoria</label>
                        <input type="text" class="form-control" id="recipe_category_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="recipe_category_description">Descrição</label>
                        <textarea class="form-control" id="recipe_category_description" name="description" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100">Salvar categoria</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xxl-4">
        <div class="category-board">
            <?php foreach ($recipeGroups as $group): ?>
                <section class="card category-card" id="recipe-category-<?= $group['id'] ?>">
                    <div class="card-body">
                        <div class="category-header">
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($group['name']) ?></h5>
                                <?php if (! empty($group['description'])): ?>
                                    <p class="text-muted small mb-0"><?= htmlspecialchars($group['description']) ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="badge rounded-pill bg-primary-subtle text-primary"><?= count($group['recipes']) ?> receita(s)</span>
                        </div>
                        <?php if (empty($group['recipes'])): ?>
                            <p class="text-muted mb-0">Nenhuma receita cadastrada nesta categoria.</p>
                        <?php else: ?>
                            <div class="recipe-list">
                                <?php foreach ($group['recipes'] as $recipe): ?>
                                    <article class="recipe-card" id="recipe-<?= $recipe['id'] ?>">
                                        <div class="recipe-media">
                                            <?php if (! empty($recipe['image_path'])): ?>
                                                <img src="<?= htmlspecialchars($recipe['image_path']) ?>" alt="<?= htmlspecialchars($recipe['name']) ?>" class="recipe-image">
                                            <?php else: ?>
                                                <div class="recipe-placeholder"><i class="bi bi-journal"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="recipe-body">
                                            <h6 class="recipe-title mb-1"><?= htmlspecialchars($recipe['name']) ?></h6>
                                            <?php if (! empty($recipe['summary'])): ?>
                                                <p class="text-muted small mb-2"><?= htmlspecialchars($recipe['summary']) ?></p>
                                            <?php endif; ?>
                                            <div class="recipe-meta mb-3">
                                                <?php if (! empty($recipe['preparation_time'])): ?>
                                                    <span><i class="bi bi-clock"></i> <?= htmlspecialchars($recipe['preparation_time']) ?></span>
                                                <?php endif; ?>
                                                <?php if (! empty($recipe['yield_description'])): ?>
                                                    <span><i class="bi bi-box"></i> <?= htmlspecialchars($recipe['yield_description']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (! empty($recipe['ingredients'])): ?>
                                                <div class="recipe-section mb-3">
                                                    <h6 class="text-uppercase text-muted small mb-2">Ingredientes</h6>
                                                    <p class="mb-0 small text-body-secondary"><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <div class="recipe-section">
                                                <h6 class="text-uppercase text-muted small mb-2">Modo de preparo</h6>
                                                <p class="mb-0 small text-body-secondary"><?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p>
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
