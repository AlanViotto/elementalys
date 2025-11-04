<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;
use PDO;
use PDOException;

class SaleController extends BaseController
{
    public function all(): array
    {
        $pdo = Connection::getInstance();
        $query = 'SELECT sales.*, products.name AS product_name, customers.name AS customer_name
                  FROM sales
                  LEFT JOIN products ON products.id = sales.product_id
                  LEFT JOIN customers ON customers.id = sales.customer_id
                  ORDER BY sales.created_at DESC';

        return $pdo->query($query)->fetchAll();
    }

    public function create(array $data): void
    {
        $pdo = Connection::getInstance();
        $pdo->beginTransaction();

        try {
            $productId = $this->sanitizeInt($data['product_id'] ?? null);
            $customerId = $this->sanitizeInt($data['customer_id'] ?? null) ?: null;
            $quantity = max(1, $this->sanitizeInt($data['quantity'] ?? '1'));

            $productStatement = $pdo->prepare('SELECT cost_price, sale_price, stock_quantity FROM products WHERE id = :id FOR UPDATE');
            $productStatement->execute(['id' => $productId]);
            $product = $productStatement->fetch(PDO::FETCH_ASSOC);

            if (! $product || $product['stock_quantity'] < $quantity) {
                throw new PDOException('Estoque insuficiente para concluir a venda.');
            }

            $unitCost = (float) $product['cost_price'];
            $unitPrice = (float) $product['sale_price'];
            $totalCost = $unitCost * $quantity;
            $totalPrice = $unitPrice * $quantity;

            $saleStatement = $pdo->prepare('INSERT INTO sales (product_id, customer_id, quantity, unit_cost, unit_price, total_cost, total_price) VALUES (:product_id, :customer_id, :quantity, :unit_cost, :unit_price, :total_cost, :total_price)');
            $saleStatement->execute([
                'product_id' => $productId,
                'customer_id' => $customerId,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'unit_price' => $unitPrice,
                'total_cost' => $totalCost,
                'total_price' => $totalPrice
            ]);

            $updateStock = $pdo->prepare('UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :product_id');
            $updateStock->execute([
                'quantity' => $quantity,
                'product_id' => $productId
            ]);

            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }

    public function products(): array
    {
        $pdo = Connection::getInstance();
        return $pdo->query('SELECT id, name FROM products ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function customers(): array
    {
        $pdo = Connection::getInstance();
        return $pdo->query('SELECT id, name FROM customers ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }
}
