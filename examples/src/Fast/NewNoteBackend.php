<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Fast;

use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Examples\Common\Util\Redirect;
use Paket\Fram\Examples\Common\View\ErrorView;
use Paket\Fram\Router\Route;
use Paket\Fram\View\DefaultView;

final class NewNoteBackend implements DefaultView
{
    public const PATH = '/fast/note';

    /** @var NoteRepository */
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function render(Route $route)
    {
        $title = $_POST['title'] ?? '';
        if (empty($title)) {
            return $route->withViewClass(ErrorView::class, [400, 'Missing title']);
        }

        $text = $_POST['text'] ?? '';
        if (empty($text)) {
            return $route->withViewClass(ErrorView::class, [400, 'Missing text']);
        }

        $this->noteRepository->insertNote($title, $text);
        Redirect::reply(IndexView::PATH);
    }
}