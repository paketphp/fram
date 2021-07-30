<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Note;

use Iterator;
use Paket\Fram\Examples\Common\Util\Database;

final class NoteRepository
{
    /** @var Database */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @return Iterator|Note[]
     */
    public function getAllNotes(): Iterator
    {
        $sql = "SELECT note_id, title, text, created_at
                FROM note
                ORDER BY created_at DESC";

        $stmt = $this->database->execute($sql);
        while ($row = $stmt->fetch()) {
            yield self::hydrateNote($row);
        }
    }

    public function getNoteById(int $note_id): ?Note
    {
        $sql = "SELECT note_id, title, text, created_at
                FROM note
                WHERE note_id = ?";

        $stmt = $this->database->execute($sql, [$note_id]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return self::hydrateNote($row);
    }

    public function insertNote(string $title, string $text): void
    {
        $sql = "INSERT INTO note (title, text) VALUES (?, ?)";
        $this->database->execute($sql, [$title, $text]);
    }

    public function updateNote(int $note_id, string $title, string $text): void
    {
        $sql = "UPDATE note
                SET title = ?, text = ?
                WHERE note_id = ?";

        $this->database->execute($sql, [$title, $text, $note_id]);
    }

    public function deleteNote(int $note_id): void
    {
        $sql = "DELETE FROM note WHERE note_id = ?";
        $this->database->execute($sql, [$note_id]);
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