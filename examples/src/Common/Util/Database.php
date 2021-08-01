<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Util;

use PDO;
use PDOStatement;

final class Database
{
    /** @var PDO */
    private $pdo;

    public function __construct()
    {
        $setup = false;
        $path = __DIR__ . '/../../../data/examples.db';
        if (!is_file($path)) {
            if (!is_dir(dirname($path))) {
                mkdir(dirname($path));
            }
            $setup = true;
        }

        $this->pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        if ($setup) {
            $this->setup();
        }
    }

    public function execute(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    private function setup(): void
    {
        $note = "CREATE TABLE note (
                    note_id INTEGER,
                    title TEXT NOT NULL,
                    text TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                    PRIMARY KEY (note_id)
                );";

        $setting = "CREATE TABLE setting (
            key TEXT NOT NULL,
            value TEXT NOT NULL,
            PRIMARY KEY (key)
        );";

        $this->execute($note);
        $this->execute($setting);

        $secret = bin2hex(random_bytes(10));
        $sql = "INSERT INTO setting (key, value) VALUES (?, ?)";
        $this->execute($sql, ['secret', $secret]);
    }
}