<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;
use PDO;

class AuthController extends BaseController
{
    public function attemptLogin(string $email, string $password): bool
    {
        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $this->sanitizeString($email)]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (! $user) {
            return false;
        }

        if (! password_verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];

        return true;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
    }

    public function ensureAuthenticated(): void
    {
        if (! isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}
