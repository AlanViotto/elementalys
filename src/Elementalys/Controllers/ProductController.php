<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;
use PDO;

class ProductController extends BaseController
{
    public function all(): array
    {
        return $this->groupedByCategory();
    }

    public function groupedByCategory(): array
    {
        $pdo = Connection::getInstance();
        $categories = $this->categories();
        $grouped = [];

        foreach ($categories as $category) {
            $grouped[$category['id']] = [
                'id' => (int) $category['id'],
                'name' => $category['name'],
                'description' => $category['description'],
                'products' => [],
            ];
        }

        $grouped[0] = [
            'id' => 0,
            'name' => 'Sem categoria',
            'description' => 'Produtos aguardando categorização.',
            'products' => [],
        ];

        $query = 'SELECT p.*, s.name AS supplier_name, c.name AS category_name, r.name AS recipe_name
                  FROM products p
                  LEFT JOIN suppliers s ON s.id = p.supplier_id
                  LEFT JOIN product_categories c ON c.id = p.product_category_id
                  LEFT JOIN recipes r ON r.id = p.recipe_id
                  ORDER BY COALESCE(c.name, "Sem categoria"), p.name';

        $products = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $categoryId = $product['product_category_id'] ? (int) $product['product_category_id'] : 0;
            $grouped[$categoryId]['products'][] = $product;
        }

        return array_values($grouped);
    }

    public function create(array $data): array
    {
        $name = $this->sanitizeString($data['name'] ?? '');
        $description = $this->sanitizeLongText($data['description'] ?? '');
        $supplierId = $this->sanitizeNullableInt($data['supplier_id'] ?? null);
        $categoryId = $this->sanitizeNullableInt($data['product_category_id'] ?? null);
        $recipeId = $this->sanitizeNullableInt($data['recipe_id'] ?? null);
        $productType = strtoupper(trim($data['product_type'] ?? 'Pronto')) === 'ARTESANAL' ? 'Artesanal' : 'Pronto';
        $imagePath = $this->sanitizeUrl($data['image_path'] ?? '');
        $costPrice = max(0, $this->sanitizeFloat($data['cost_price'] ?? '0'));
        $markup = max(0, $this->sanitizeFloat($data['markup_percentage'] ?? '0'));
        $stock = max(0, $this->sanitizeInt($data['stock_quantity'] ?? '0'));
        $minStock = max(0, $this->sanitizeInt($data['min_stock_level'] ?? '0'));

        if ($name === '' || $categoryId === null) {
            return [
                'success' => false,
                'message' => 'Informe o nome e a categoria do produto.',
            ];
        }

        if ($productType === 'Artesanal' && ! $recipeId) {
            return [
                'success' => false,
                'message' => 'Produtos artesanais precisam estar vinculados a uma receita.',
            ];
        }

        $salePrice = $this->calculateSalePrice($costPrice, $markup);

        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('INSERT INTO products (name, description, supplier_id, product_category_id, recipe_id, image_path, product_type, cost_price, markup_percentage, sale_price, stock_quantity, min_stock_level) VALUES (:name, :description, :supplier_id, :category_id, :recipe_id, :image_path, :product_type, :cost_price, :markup_percentage, :sale_price, :stock_quantity, :min_stock_level)');
        $statement->execute([
            'name' => $name,
            'description' => $description,
            'supplier_id' => $supplierId,
            'category_id' => $categoryId,
            'recipe_id' => $recipeId,
            'image_path' => $imagePath,
            'product_type' => $productType,
            'cost_price' => $costPrice,
            'markup_percentage' => $markup,
            'sale_price' => $salePrice,
            'stock_quantity' => $stock,
            'min_stock_level' => $minStock,
        ]);

        return [
            'success' => true,
            'message' => 'Produto cadastrado com sucesso.',
        ];
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

    public function categories(): array
    {
        $pdo = Connection::getInstance();

        return $pdo->query('SELECT id, name, description FROM product_categories ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCategory(array $data): array
    {
        $name = $this->sanitizeString($data['name'] ?? '');
        $description = $this->sanitizeString($data['description'] ?? '');

        if ($name === '') {
            return [
                'success' => false,
                'message' => 'Informe um nome para a categoria.',
            ];
        }

        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('INSERT INTO product_categories (name, description) VALUES (:name, :description)');
        $statement->execute([
            'name' => $name,
            'description' => $description,
        ]);

        return [
            'success' => true,
            'message' => 'Categoria criada com sucesso.',
        ];
    }

    public function suppliers(): array
    {
        $pdo = Connection::getInstance();
        return $pdo->query('SELECT id, name FROM suppliers ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }
}
