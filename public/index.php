<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

use Elementalys\Controllers\AuthController;
use Elementalys\Controllers\CustomerController;
use Elementalys\Controllers\DashboardController;
use Elementalys\Controllers\ProductController;
use Elementalys\Controllers\SaleController;
use Elementalys\Controllers\SupplierController;

autoload();

session_start();

$authController = new AuthController();
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? null;
$error = null;

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

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $productController->create($_POST);
                header('Location: index.php?page=products&success=1');
                exit;
            }

            $products = $productController->all();
            $suppliers = $supplierController->all();
            require __DIR__ . '/../views/products/index.php';
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

        case 'dashboard':
        default:
            $dashboardController = new DashboardController();
            $productController = new ProductController();

            $stats = $dashboardController->statistics();
            $lowStock = $productController->lowStock();

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
