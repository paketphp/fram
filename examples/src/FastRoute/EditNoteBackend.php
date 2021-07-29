<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\FastRoute;

use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Examples\Common\Util\Redirect;
use Paket\Fram\Examples\Common\View\View404;
use Paket\Fram\Router\Route;
use Paket\Fram\View\SimpleView;

final class EditNoteBackend implements SimpleView
{
    public const PATH = '/fastroute/note/{note_id}/edit';

    /** @var NoteRepository */
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function render(Route $route)
    {
        $payload = $route->getPayload();
        $note_id = filter_var($payload[2]['note_id'] ?? false, FILTER_VALIDATE_INT);
        if ($note_id === false) {
            return $route->withViewClass(View404::class);
        }

        $title = $_POST['title'] ?? '';
        if (empty($title)) {
            return $route->withViewClass(View404::class);
        }

        $text = $_POST['text'] ?? '';
        if (empty($text)) {
            return $route->withViewClass(View404::class);
        }

        $this->noteRepository->updateNote($note_id, $title, $text);
        Redirect::reply(IndexView::PATH);
    }
}