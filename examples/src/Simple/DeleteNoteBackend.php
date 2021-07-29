<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Simple;

use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Examples\Common\Util\Redirect;
use Paket\Fram\Examples\Common\View\View404;
use Paket\Fram\Router\Route;
use Paket\Fram\View\SimpleView;

final class DeleteNoteBackend implements SimpleView
{
    public const PATH = '/simple/note/delete';

    /** @var NoteRepository */
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function render(Route $route)
    {
        $note_id = filter_var($_POST['note_id'] ?? false, FILTER_VALIDATE_INT);
        if ($note_id === false) {
            return $route->withViewClass(View404::class);
        }

        $this->noteRepository->deleteNote($note_id);
        Redirect::reply(IndexView::PATH);
    }
}