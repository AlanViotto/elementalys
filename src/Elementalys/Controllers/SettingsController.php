<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;
use PDO;
use PDOException;

class SettingsController extends BaseController
{
    public function getBranding(): array
    {
        $defaults = [
            'app_name' => 'Elementalys Controle',
            'brand_tagline' => 'Velas e aromas 100% artesanais.',
            'logo_url' => '',
        ];

        $settings = $this->fetchSettings(array_keys($defaults));

        return array_merge($defaults, $settings);
    }

    public function updateBranding(array $data): array
    {
        $appName = $this->sanitizeString($data['app_name'] ?? '');
        $tagline = $this->sanitizeString($data['brand_tagline'] ?? '');
        $logoUrl = $this->sanitizeUrl($data['logo_url'] ?? '');

        if ($appName === '') {
            return [
                'success' => false,
                'message' => 'Informe o nome da sua marca.',
            ];
        }

        $this->saveSetting('app_name', $appName);
        $this->saveSetting('brand_tagline', $tagline);
        $this->saveSetting('logo_url', $logoUrl);

        return [
            'success' => true,
            'message' => 'Identidade visual atualizada com sucesso.',
        ];
    }

    public function getUserProfile(int $userId): array
    {
        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('SELECT id, name, email FROM users WHERE id = :id');
        $statement->execute(['id' => $userId]);

        return $statement->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function updateUserProfile(int $userId, array $data): array
    {
        $name = $this->sanitizeString($data['name'] ?? '');
        $email = $this->sanitizeEmail($data['email'] ?? '');
        $currentPassword = $data['current_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        if ($name === '' || $email === '') {
            return [
                'success' => false,
                'message' => 'Nome e e-mail são obrigatórios.',
            ];
        }

        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('SELECT password_hash FROM users WHERE id = :id');
        $statement->execute(['id' => $userId]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (! $user) {
            return [
                'success' => false,
                'message' => 'Usuário não encontrado.',
            ];
        }

        if ($newPassword !== '' || $confirmPassword !== '') {
            if ($newPassword !== $confirmPassword) {
                return [
                    'success' => false,
                    'message' => 'A confirmação de senha não confere.',
                ];
            }

            if (! password_verify($currentPassword, $user['password_hash'])) {
                return [
                    'success' => false,
                    'message' => 'Informe a senha atual para alterá-la.',
                ];
            }
        }

        if ($email !== '') {
            $uniqueStatement = $pdo->prepare('SELECT id FROM users WHERE email = :email AND id <> :id');
            $uniqueStatement->execute([
                'email' => $email,
                'id' => $userId,
            ]);

            if ($uniqueStatement->fetch()) {
                return [
                    'success' => false,
                    'message' => 'Já existe um usuário com este e-mail.',
                ];
            }
        }

        try {
            $pdo->beginTransaction();

            $updateQuery = 'UPDATE users SET name = :name, email = :email%s WHERE id = :id';
            $params = [
                'name' => $name,
                'email' => $email,
                'id' => $userId,
            ];

            if ($newPassword !== '') {
                $updateQuery = sprintf($updateQuery, ', password_hash = :hash');
                $params['hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
            } else {
                $updateQuery = sprintf($updateQuery, '');
            }

            $pdo->prepare($updateQuery)->execute($params);
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollBack();

            return [
                'success' => false,
                'message' => 'Não foi possível atualizar o usuário: ' . $exception->getMessage(),
            ];
        }

        $_SESSION['user_name'] = $name;

        return [
            'success' => true,
            'message' => 'Dados atualizados com sucesso.',
        ];
    }

    private function fetchSettings(array $keys): array
    {
        if (empty($keys)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($keys), '?'));
        $pdo = Connection::getInstance();
        $statement = $pdo->prepare("SELECT key_name, value FROM settings WHERE key_name IN ($placeholders)");
        $statement->execute($keys);

        return $statement->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
    }

    private function saveSetting(string $key, string $value): void
    {
        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('INSERT INTO settings (key_name, value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE value = VALUES(value)');
        $statement->execute([
            'key' => $key,
            'value' => $value,
        ]);
    }
}
