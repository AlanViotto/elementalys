<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;
use PDO;
use Throwable;

class SupplyController extends BaseController
{
    public function all(): array
    {
        $pdo = Connection::getInstance();
        $query = 'SELECT s.*, sup.name AS supplier_name FROM supplies s LEFT JOIN suppliers sup ON sup.id = s.supplier_id ORDER BY s.name';

        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): array
    {
        $name = $this->sanitizeString($data['name'] ?? '');
        $supplierId = $this->sanitizeNullableInt($data['supplier_id'] ?? null);
        $unit = $this->sanitizeString($data['unit'] ?? '');
        $stock = max(0, $this->sanitizeInt($data['stock_quantity'] ?? '0'));
        $minStock = max(0, $this->sanitizeInt($data['min_stock_level'] ?? '0'));
        $costPerUnit = max(0, $this->sanitizeFloat($data['cost_per_unit'] ?? '0'));
        $notes = $this->sanitizeLongText($data['notes'] ?? '');

        if ($name === '') {
            return [
                'success' => false,
                'message' => 'Informe o nome do insumo.',
            ];
        }

        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('INSERT INTO supplies (name, supplier_id, unit, stock_quantity, min_stock_level, cost_per_unit, notes) VALUES (:name, :supplier_id, :unit, :stock_quantity, :min_stock_level, :cost_per_unit, :notes)');
        $statement->execute([
            'name' => $name,
            'supplier_id' => $supplierId,
            'unit' => $unit,
            'stock_quantity' => $stock,
            'min_stock_level' => $minStock,
            'cost_per_unit' => $costPerUnit,
            'notes' => $notes,
        ]);

        return [
            'success' => true,
            'message' => 'Insumo cadastrado com sucesso.',
        ];
    }

    public function adjustStock(array $data): array
    {
        $supplyId = $this->sanitizeInt($data['supply_id'] ?? '0');
        $movement = strtolower($this->sanitizeString($data['movement'] ?? 'entrada'));
        $quantity = max(0, $this->sanitizeInt($data['quantity'] ?? '0'));

        if ($supplyId <= 0) {
            return [
                'success' => false,
                'message' => 'Selecione um insumo válido.',
            ];
        }

        if ($quantity <= 0) {
            return [
                'success' => false,
                'message' => 'Informe uma quantidade maior que zero.',
            ];
        }

        $pdo = Connection::getInstance();

        try {
            $pdo->beginTransaction();

            $statement = $pdo->prepare('SELECT stock_quantity FROM supplies WHERE id = :id FOR UPDATE');
            $statement->execute(['id' => $supplyId]);
            $currentStock = $statement->fetchColumn();

            if ($currentStock === false) {
                $pdo->rollBack();

                return [
                    'success' => false,
                    'message' => 'Insumo não encontrado.',
                ];
            }

            $currentStock = (int) $currentStock;
            $newStock = $currentStock;

            if ($movement === 'saida' || $movement === 'saída' || $movement === 'out') {
                if ($quantity > $currentStock) {
                    $pdo->rollBack();

                    return [
                        'success' => false,
                        'message' => 'A quantidade informada excede o estoque disponível.',
                    ];
                }

                $newStock = $currentStock - $quantity;
            } else {
                $newStock = $currentStock + $quantity;
            }

            $update = $pdo->prepare('UPDATE supplies SET stock_quantity = :stock_quantity WHERE id = :id');
            $update->execute([
                'stock_quantity' => $newStock,
                'id' => $supplyId,
            ]);

            $pdo->commit();

            return [
                'success' => true,
                'message' => 'Estoque atualizado com sucesso.',
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return [
                'success' => false,
                'message' => 'Não foi possível atualizar o estoque. Tente novamente.',
            ];
        }
    }

    public function lowStock(int $threshold = 0): array
    {
        $pdo = Connection::getInstance();
        $query = 'SELECT id, name, unit, stock_quantity, min_stock_level FROM supplies WHERE stock_quantity <= GREATEST(min_stock_level, :threshold) ORDER BY stock_quantity ASC';
        $statement = $pdo->prepare($query);
        $statement->execute(['threshold' => $threshold]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
