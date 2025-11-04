<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= htmlspecialchars(($branding['app_name'] ?? 'Elementalys Controle')) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm login-card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php if (! empty($branding['logo_url'])): ?>
                            <img src="<?= htmlspecialchars($branding['logo_url']) ?>" alt="<?= htmlspecialchars($branding['app_name']) ?>" class="login-logo mb-3">
                        <?php endif; ?>
                        <h1 class="h4 fw-semibold mb-1"><?= htmlspecialchars($branding['app_name']) ?></h1>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($branding['brand_tagline'] ?? '') ?></p>
                    </div>
                    <?php if (! empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" action="index.php?page=login">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
            <p class="text-center mt-3 text-muted small"><?= htmlspecialchars($branding['brand_tagline'] ?? '') ?></p>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
