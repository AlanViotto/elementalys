<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;

class SupplierController extends BaseController
{
    public function all(): array
    {
        $pdo = Connection::getInstance();
        return $pdo->query('SELECT * FROM suppliers ORDER BY name')->fetchAll();
    }

    public function create(array $data): void
    {
        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('INSERT INTO suppliers (name, contact_name, email, phone, notes) VALUES (:name, :contact_name, :email, :phone, :notes)');
        $statement->execute([
            'name' => $this->sanitizeString($data['name'] ?? ''),
            'contact_name' => $this->sanitizeString($data['contact_name'] ?? ''),
            'email' => $this->sanitizeString($data['email'] ?? ''),
            'phone' => $this->sanitizeString($data['phone'] ?? ''),
            'notes' => $this->sanitizeString($data['notes'] ?? '')
        ]);
    }
}
