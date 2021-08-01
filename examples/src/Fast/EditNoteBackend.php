<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Fast;

use Paket\Fram\Examples\Common\Note\Note;
use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Examples\Common\Util\Redirect;
use Paket\Fram\Examples\Common\View\ErrorView;
use Paket\Fram\Examples\Common\ViewHandler\FormBackend;
use Paket\Fram\Router\FastRoute;
use Paket\Fram\Router\Route;

final class EditNoteBackend implements FormBackend
{
    public const PATH = '/fast/notes/{note_id}/edit';

    /** @var NoteRepository */
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public static function buildPath(Note $note): string
    {
        return strtr(self::PATH, ['{note_id}' => $note->note_id]);
    }

    public function render(Route $route)
    {
        /** @var FastRoute $route */
        $note_id = filter_var($route->getRouteVar('note_id'), FILTER_VALIDATE_INT);
        if ($note_id === false) {
            return $route->withViewClass(ErrorView::class, [400, 'Missing note_id']);
        }

        $title = $_POST['title'] ?? '';
        if (empty($title)) {
            return $route->withViewClass(ErrorView::class, [400, 'Missing title']);
        }

        $text = $_POST['text'] ?? '';
        if (empty($text)) {
            return $route->withViewClass(ErrorView::class, [400, 'Missing text']);
        }

        $this->noteRepository->updateNote($note_id, $title, $text);
        Redirect::reply(IndexView::PATH);
    }
}