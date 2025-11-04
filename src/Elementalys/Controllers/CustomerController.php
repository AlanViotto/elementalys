<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;

class CustomerController extends BaseController
{
    public function all(): array
    {
        $pdo = Connection::getInstance();
        return $pdo->query('SELECT * FROM customers ORDER BY name')->fetchAll();
    }

    public function create(array $data): void
    {
        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('INSERT INTO customers (name, email, phone, address) VALUES (:name, :email, :phone, :address)');
        $statement->execute([
            'name' => $this->sanitizeString($data['name'] ?? ''),
            'email' => $this->sanitizeString($data['email'] ?? ''),
            'phone' => $this->sanitizeString($data['phone'] ?? ''),
            'address' => $this->sanitizeString($data['address'] ?? '')
        ]);
    }
}
