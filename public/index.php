<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

use Elementalys\Controllers\AuthController;
use Elementalys\Controllers\CustomerController;
use Elementalys\Controllers\DashboardController;
use Elementalys\Controllers\ProductController;
use Elementalys\Controllers\RecipeController;
use Elementalys\Controllers\SaleController;
use Elementalys\Controllers\SettingsController;
use Elementalys\Controllers\SupplierController;
use Elementalys\Controllers\SupplyController;

autoload();

session_start();

$authController = new AuthController();
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? null;
$error = null;
$settingsController = new SettingsController();
$branding = $settingsController->getBranding();

if ($action === 'logout') {
    $authController->logout();
    header('Location: index.php?page=login');
    exit;
}

if ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (! $authController->attemptLogin($email, $password)) {
            $error = 'Credenciais inválidas. Tente novamente.';
        } else {
            header('Location: index.php');
            exit;
        }
    }

    require __DIR__ . '/../views/auth/login.php';
    exit;
}

$authController->ensureAuthenticated();

try {
    switch ($page) {
        case 'products':
            $productController = new ProductController();
            $supplierController = new SupplierController();
            $recipeController = new RecipeController();
            $productFeedback = null;
            $categoryFeedback = null;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $form = $_POST['form'] ?? 'product';

                if ($form === 'category') {
                    $categoryFeedback = $productController->createCategory($_POST);
                } else {
                    $productFeedback = $productController->create($_POST);
                }
            }

            $productGroups = $productController->groupedByCategory();
            $productCategories = $productController->categories();
            $suppliers = $supplierController->all();
            $recipes = $recipeController->forSelect();
            require __DIR__ . '/../views/products/index.php';
            break;

        case 'recipes':
            $recipeController = new RecipeController();
            $recipeFeedback = null;
            $recipeCategoryFeedback = null;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $form = $_POST['form'] ?? 'recipe';

                if ($form === 'category') {
                    $recipeCategoryFeedback = $recipeController->createCategory($_POST);
                } else {
                    $recipeFeedback = $recipeController->create($_POST);
                }
            }

            $recipeGroups = $recipeController->groupedByCategory();
            $recipeCategories = $recipeController->categories();

            require __DIR__ . '/../views/recipes/index.php';
            break;

        case 'supplies':
            $supplyController = new SupplyController();
            $supplierController = new SupplierController();
            $supplyFeedback = null;
            $stockFeedback = null;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $form = $_POST['form'] ?? 'supply';

                if ($form === 'stock') {
                    $stockFeedback = $supplyController->adjustStock($_POST);
                } else {
                    $supplyFeedback = $supplyController->create($_POST);
                }
            }

            $supplies = $supplyController->all();
            $suppliers = $supplierController->all();

            require __DIR__ . '/../views/supplies/index.php';
            break;

        case 'customers':
            $customerController = new CustomerController();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $customerController->create($_POST);
                header('Location: index.php?page=customers&success=1');
                exit;
            }

            $customers = $customerController->all();
            require __DIR__ . '/../views/customers/index.php';
            break;

        case 'suppliers':
            $supplierController = new SupplierController();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $supplierController->create($_POST);
                header('Location: index.php?page=suppliers&success=1');
                exit;
            }

            $suppliers = $supplierController->all();
            require __DIR__ . '/../views/suppliers/index.php';
            break;

        case 'sales':
            $saleController = new SaleController();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $saleController->create($_POST);
                header('Location: index.php?page=sales&success=1');
                exit;
            }

            $sales = $saleController->all();
            $products = $saleController->products();
            $customers = $saleController->customers();
            require __DIR__ . '/../views/sales/index.php';
            break;

        case 'settings':
            $profileFeedback = null;
            $brandingFeedback = null;
            $profile = $settingsController->getUserProfile((int) ($_SESSION['user_id'] ?? 0));

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $form = $_POST['form'] ?? 'branding';

                if ($form === 'profile') {
                    $profileFeedback = $settingsController->updateUserProfile((int) $_SESSION['user_id'], $_POST);
                    $profile = $settingsController->getUserProfile((int) $_SESSION['user_id']);
                } else {
                    $brandingFeedback = $settingsController->updateBranding($_POST);
                    $branding = $settingsController->getBranding();
                }
            }

            require __DIR__ . '/../views/settings/index.php';
            break;

        case 'dashboard':
        default:
            $dashboardController = new DashboardController();
            $productController = new ProductController();
            $supplyController = new SupplyController();

            $stats = $dashboardController->statistics();
            $lowStockProducts = $productController->lowStock();
            $lowStockSupplies = $supplyController->lowStock();

            require __DIR__ . '/../views/dashboard/index.php';
            break;
    }
} catch (Throwable $exception) {
    http_response_code(500);
    require __DIR__ . '/../views/layout/header.php';
    ?>
    <div class="alert alert-danger">
        <h5 class="alert-heading">Ocorreu um erro inesperado</h5>
        <p><?= htmlspecialchars($exception->getMessage()) ?></p>
    </div>
    <?php
    require __DIR__ . '/../views/layout/footer.php';
}

function autoload(): void
{
    $autoloadPath = __DIR__ . '/../vendor/autoload.php';

    if (! file_exists($autoloadPath)) {
        throw new RuntimeException('O autoload do Composer não foi encontrado. Execute "composer install".');
    }

    require $autoloadPath;
}
