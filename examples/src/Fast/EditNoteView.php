<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Fast;

use Paket\Fram\Examples\Common\Component\EditNoteComponent;
use Paket\Fram\Examples\Common\Component\FootComponent;
use Paket\Fram\Examples\Common\Component\HeadComponent;
use Paket\Fram\Examples\Common\Note\Note;
use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Examples\Common\View\ErrorView;
use Paket\Fram\Examples\Common\View\View404;
use Paket\Fram\Router\FastRoute;
use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

final class EditNoteView implements HtmlView
{
    public const PATH = '/fast/note/{note_id}/edit';

    /** @var HeadComponent */
    private $head;
    /** @var FootComponent */
    private $foot;
    /** @var NoteRepository */
    private $noteRepository;
    /** @var EditNoteComponent */
    private $editNote;

    public function __construct(
        HeadComponent     $head,
        FootComponent     $foot,
        NoteRepository    $noteRepository,
        EditNoteComponent $editNote)
    {
        $this->head = $head;
        $this->foot = $foot;
        $this->noteRepository = $noteRepository;
        $this->editNote = $editNote;
    }

    public function render(Route $route)
    {
        /** @var FastRoute $route */
        $note_id = filter_var($route->getRouteVar('note_id'), FILTER_VALIDATE_INT);
        if ($note_id === false) {
            return $route->withViewClass(ErrorView::class, [400, 'Missing note_id']);
        }

        $note = $this->noteRepository->getNoteById($note_id);
        if ($note === null) {
            return $route->withViewClass(View404::class);
        }

        $this->head->render('Fast Notes');
        ?>
        <div class="container">
            <h1>Edit note</h1>
            <?php $this->editNote->render($note, function (Note $note) {
                return strtr(EditNoteBackend::PATH, ['{note_id}' => $note->note_id]);
            }, function (Note $note) {
                return strtr(DeleteNoteBackend::PATH, ['{note_id}' => $note->note_id]);
            }, false); ?>
        </div>
        <?php
        $this->foot->render();
    }
}