<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Note;

use Iterator;
use PDO;
use PDOStatement;
use RuntimeException;

class NoteRepository
{
    /** @var PDO */
    private $pdo;

    public function __construct()
    {
        $path = __DIR__ . '/../../../data/examples.db';
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path));
        }
        $setup = false;
        if (!is_file($path)) {
            $setup = true;
        }

        $this->pdo = new PDO('sqlite:' . $path);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($setup) {
            $this->setup();
        }
    }

    /**
     * @return Iterator|Note[]
     */
    public function getAllNotes(): Iterator
    {
        $sql = "SELECT note_id, title, text
                FROM note";

        $stmt = $this->execute($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $note = new Note();
            $note->note_id = (int) $row['note_id'];
            $note->title = $row['title'];
            $note->text = $row['text'];
            yield $note;
        }
    }

    public function insertNote(string $title, string $text): void
    {
        $sql = "INSERT INTO note (title, text) VALUES (?, ?)";
        $this->execute($sql, [$title, $text]);
    }

    private function setup()
    {
        $sql = "CREATE TABLE note (
                    note_id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT NOT NULL,
                    text TEXT NOT NULL
                );";
        $this->execute($sql);
    }

    private function execute(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute($params);
        if ($success === false) {
            throw new RuntimeException("Failed executing SQL {$sql}");
        }
        return $stmt;
    }
}