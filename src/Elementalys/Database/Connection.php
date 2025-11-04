<?php

namespace Elementalys\Database;

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];

            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $db['host'], $db['port'], $db['database'], $db['charset']);

            try {
                self::$instance = new PDO($dsn, $db['username'], $db['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $exception) {
                throw new PDOException('Erro ao conectar com o banco de dados: ' . $exception->getMessage(), (int) $exception->getCode(), $exception);
            }
        }

        return self::$instance;
    }
}
