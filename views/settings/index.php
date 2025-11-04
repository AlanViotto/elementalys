<?php
/** @var array $profile */
/** @var array $branding */
/** @var array|null $profileFeedback */
/** @var array|null $brandingFeedback */
$pageTitle = 'Configurações';
$activeMenu = 'settings';
require __DIR__ . '/../layout/header.php';
?>
<div class="row g-4">
    <div class="col-xxl-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Perfil de acesso</h5>
                <p class="text-muted small">Atualize seus dados de login. Para alterar a senha, informe a senha atual.</p>
                <?php if ($profileFeedback): ?>
                    <div class="alert alert-<?= $profileFeedback['success'] ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($profileFeedback['message']) ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="index.php?page=settings" class="form-styled">
                    <input type="hidden" name="form" value="profile">
                    <div class="mb-3">
                        <label class="form-label" for="user_name">Nome</label>
                        <input type="text" class="form-control" id="user_name" name="name" value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="user_email">E-mail</label>
                        <input type="email" class="form-control" id="user_email" name="email" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="current_password">Senha atual</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Obrigatória para alterar a senha">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="new_password">Nova senha</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Mínimo 8 caracteres">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label" for="confirm_password">Confirme a nova senha</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Salvar alterações</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xxl-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Identidade visual</h5>
                <p class="text-muted small">Personalize o nome da marca, slogan e logotipo exibido no painel e no login.</p>
                <?php if ($brandingFeedback): ?>
                    <div class="alert alert-<?= $brandingFeedback['success'] ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($brandingFeedback['message']) ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="index.php?page=settings" class="form-styled">
                    <input type="hidden" name="form" value="branding">
                    <div class="mb-3">
                        <label class="form-label" for="brand_name">Nome da marca</label>
                        <input type="text" class="form-control" id="brand_name" name="app_name" value="<?= htmlspecialchars($branding['app_name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="brand_tagline">Slogan</label>
                        <input type="text" class="form-control" id="brand_tagline" name="brand_tagline" value="<?= htmlspecialchars($branding['brand_tagline'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="brand_logo">URL do logotipo</label>
                        <input type="url" class="form-control" id="brand_logo" name="logo_url" value="<?= htmlspecialchars($branding['logo_url'] ?? '') ?>" placeholder="https://...">
                        <small class="text-muted">Use imagens quadradas em formato PNG ou SVG hospedadas em local seguro.</small>
                    </div>
                    <?php if (! empty($branding['logo_url'])): ?>
                        <div class="preview-logo mt-3 p-3 border rounded text-center">
                            <p class="text-muted small mb-2">Pré-visualização</p>
                            <img src="<?= htmlspecialchars($branding['logo_url']) ?>" alt="Pré-visualização do logotipo" class="preview-logo-img">
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-outline-primary mt-3">Atualizar identidade</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php';
