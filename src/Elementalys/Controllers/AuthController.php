<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;
use PDO;

class AuthController extends BaseController
{
    private const DEFAULT_ADMIN_EMAIL = 'admin@elementalys.com';
    private const DEFAULT_ADMIN_PASSWORD = 'admin123';

    public function attemptLogin(string $email, string $password): bool
    {
        $pdo = Connection::getInstance();
        $sanitizedEmail = $this->sanitizeEmail($email);

        $statement = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $sanitizedEmail]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $this->startUserSession($user);
            $this->refreshPasswordIfNeeded($pdo, (int) $user['id'], $password, $user['password_hash']);

            return true;
        }

        if ($this->isDefaultAdminCredentials($sanitizedEmail, $password)) {
            if ($user) {
                $user['password_hash'] = $this->updatePasswordHash($pdo, (int) $user['id'], $password);
            } else {
                $user = $this->createDefaultAdminUser($pdo, $password);
            }

            $this->startUserSession($user);

            return true;
        }

        return false;
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

    private function startUserSession(array $user): void
    {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
    }

    private function refreshPasswordIfNeeded(PDO $pdo, int $userId, string $password, string $currentHash): void
    {
        if (! password_needs_rehash($currentHash, PASSWORD_DEFAULT)) {
            return;
        }

        $this->updatePasswordHash($pdo, $userId, $password);
    }

    private function isDefaultAdminCredentials(string $email, string $password): bool
    {
        $normalizedEmail = strtolower($email);

        return hash_equals(self::DEFAULT_ADMIN_EMAIL, $normalizedEmail)
            && hash_equals(self::DEFAULT_ADMIN_PASSWORD, $password);
    }

    private function createDefaultAdminUser(PDO $pdo, string $password): array
    {
        $statement = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :hash)');
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $statement->execute([
            'name' => 'Administrador',
            'email' => self::DEFAULT_ADMIN_EMAIL,
            'hash' => $hash,
        ]);

        return [
            'id' => (int) $pdo->lastInsertId(),
            'name' => 'Administrador',
            'password_hash' => $hash,
        ];
    }

    private function updatePasswordHash(PDO $pdo, int $userId, string $password): string
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $statement = $pdo->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
        $statement->execute([
            'hash' => $hash,
            'id' => $userId,
        ]);

        return $hash;
    }
}
