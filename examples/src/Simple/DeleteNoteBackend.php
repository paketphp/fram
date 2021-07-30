<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Simple;

use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Examples\Common\Util\Redirect;
use Paket\Fram\Examples\Common\View\ErrorView;
use Paket\Fram\Router\Route;
use Paket\Fram\View\DefaultView;

final class DeleteNoteBackend implements DefaultView
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
            return $route->withViewClass(ErrorView::class, [400, 'Missing note_id']);
        }

        $this->noteRepository->deleteNote($note_id);
        Redirect::reply(IndexView::PATH);
    }
}