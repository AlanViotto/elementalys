<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;

class DashboardController
{
    public function statistics(): array
    {
        $pdo = Connection::getInstance();

        $products = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $customers = (int) $pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn();
        $suppliers = (int) $pdo->query('SELECT COUNT(*) FROM suppliers')->fetchColumn();
        $sales = (int) $pdo->query('SELECT COUNT(*) FROM sales')->fetchColumn();
        $revenue = (float) $pdo->query('SELECT IFNULL(SUM(total_price), 0) FROM sales')->fetchColumn();

        return compact('products', 'customers', 'suppliers', 'sales', 'revenue');
    }
}
