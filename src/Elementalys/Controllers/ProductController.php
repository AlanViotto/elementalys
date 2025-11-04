<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;
use PDO;

class ProductController extends BaseController
{
    public function all(): array
    {
        $pdo = Connection::getInstance();
        $query = 'SELECT p.*, s.name AS supplier_name FROM products p LEFT JOIN suppliers s ON s.id = p.supplier_id ORDER BY p.name';
        return $pdo->query($query)->fetchAll();
    }

    public function create(array $data): void
    {
        $pdo = Connection::getInstance();
        $name = $this->sanitizeString($data['name'] ?? '');
        $description = $this->sanitizeString($data['description'] ?? '');
        $supplierId = $this->sanitizeInt($data['supplier_id'] ?? null) ?: null;
        $costPrice = $this->sanitizeFloat($data['cost_price'] ?? '0');
        $markup = $this->sanitizeFloat($data['markup_percentage'] ?? '0');
        $stock = $this->sanitizeInt($data['stock_quantity'] ?? '0');
        $minStock = $this->sanitizeInt($data['min_stock_level'] ?? '0');

        $salePrice = $this->calculateSalePrice($costPrice, $markup);

        $statement = $pdo->prepare('INSERT INTO products (name, description, supplier_id, cost_price, markup_percentage, sale_price, stock_quantity, min_stock_level) VALUES (:name, :description, :supplier_id, :cost_price, :markup_percentage, :sale_price, :stock_quantity, :min_stock_level)');
        $statement->execute([
            'name' => $name,
            'description' => $description,
            'supplier_id' => $supplierId,
            'cost_price' => $costPrice,
            'markup_percentage' => $markup,
            'sale_price' => $salePrice,
            'stock_quantity' => $stock,
            'min_stock_level' => $minStock
        ]);
    }

    public function calculateSalePrice(float $costPrice, float $markupPercentage): float
    {
        $markup = $costPrice * ($markupPercentage / 100);
        return round($costPrice + $markup, 2);
    }

    public function lowStock(int $threshold = 0): array
    {
        $pdo = Connection::getInstance();
        $query = 'SELECT id, name, stock_quantity, min_stock_level FROM products WHERE stock_quantity <= GREATEST(min_stock_level, :threshold) ORDER BY stock_quantity ASC';
        $statement = $pdo->prepare($query);
        $statement->execute(['threshold' => $threshold]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function suppliers(): array
    {
        $pdo = Connection::getInstance();
        return $pdo->query('SELECT id, name FROM suppliers ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }
}
