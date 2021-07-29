<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Note;

use Iterator;
use PDO;
use PDOStatement;

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

        $this->pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        if ($setup) {
            $this->setup();
        }
    }

    /**
     * @return Iterator|Note[]
     */
    public function getAllNotes(): Iterator
    {
        $sql = "SELECT note_id, title, text, created_at
                FROM note
                ORDER BY created_at DESC";

        $stmt = $this->execute($sql);
        while ($row = $stmt->fetch()) {
            yield self::hydrateNote($row);
        }
    }

    public function getNoteById(int $note_id): ?Note
    {
        $sql = "SELECT note_id, title, text, created_at
                FROM note
                WHERE note_id = ?";

        $stmt = $this->execute($sql, [$note_id]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return self::hydrateNote($row);
    }

    public function insertNote(string $title, string $text): void
    {
        $sql = "INSERT INTO note (title, text) VALUES (?, ?)";
        $this->execute($sql, [$title, $text]);
    }

    public function updateNote(int $note_id, string $title, string $text): void
    {
        $sql = "UPDATE note
                SET title = ?, text = ?
                WHERE note_id = ?";

        $this->execute($sql, [$title, $text, $note_id]);
    }

    public function deleteNote(int $note_id): void
    {
        $sql = "DELETE FROM note WHERE note_id = ?";
        $this->execute($sql, [$note_id]);
    }

    private function setup(): void
    {
        $sql = "CREATE TABLE note (
                    note_id INTEGER,
                    title TEXT NOT NULL,
                    text TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                    PRIMARY KEY (note_id)
                );";
        $this->execute($sql);
    }

    private function execute(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    private static function hydrateNote(array $row): Note
    {
        $note = new Note();
        $note->note_id = (int)$row['note_id'];
        $note->title = $row['title'];
        $note->text = $row['text'];
        $note->created_at = $row['created_at'];
        return $note;
    }
}