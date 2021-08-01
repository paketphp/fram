<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Simple;

use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Examples\Common\Util\Redirect;
use Paket\Fram\Examples\Common\View\ErrorView;
use Paket\Fram\Examples\Common\ViewHandler\FormBackend;
use Paket\Fram\Router\Route;

final class EditNoteBackend implements FormBackend
{
    public const PATH = '/simple/note/edit';

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