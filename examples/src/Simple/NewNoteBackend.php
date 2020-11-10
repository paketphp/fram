<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Simple;

use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Examples\Common\Util\Redirect;
use Paket\Fram\Examples\Common\View\View404;
use Paket\Fram\Router\Route;
use Paket\Fram\View\SimpleView;

class NewNoteBackend implements SimpleView
{
    public const PATH = '/simple/note/new';

    public function render(Route $route)
    {
        $title = $_POST['title'] ?? '';
        if (empty($title)) {
            return $route->withViewClass(View404::class);
        }

        $text = $_POST['text'] ?? '';
        if (empty($text)) {
            return $route->withViewClass(View404::class);
        }

        $noteRepository = new NoteRepository();
        $noteRepository->insertNote($title, $text);
        Redirect::reply(IndexView::PATH);
    }
}