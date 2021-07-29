<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\FastRoute;

use Paket\Fram\Examples\Common\Component\FootComponent;
use Paket\Fram\Examples\Common\Component\HeadComponent;
use Paket\Fram\Examples\Common\Component\NoteListComponent;
use Paket\Fram\Examples\Common\Note\Note;
use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

final class IndexView implements HtmlView
{
    public const PATH = '/fastroute/';

    /** @var HeadComponent */
    private $head;
    /** @var FootComponent */
    private $foot;
    /** @var NoteListComponent */
    private $noteList;
    /** @var NoteRepository */
    private $noteRepository;

    public function __construct(
        HeadComponent     $head,
        FootComponent     $foot,
        NoteListComponent $noteList,
        NoteRepository    $noteRepository)
    {
        $this->head = $head;
        $this->foot = $foot;
        $this->noteList = $noteList;
        $this->noteRepository = $noteRepository;
    }

    public function render(Route $route)
    {
        $this->head->render('FastRoute Notes');
        ?>
        <div class="container">
            <h1>FastRoute Notes</h1>
            <a href="<?= NewNoteView::PATH ?>">New note</a>
            <?php $this->noteList->render($this->noteRepository->getAllNotes(), function (Note $note) {
                return strtr(EditNoteView::PATH , ['{note_id}' => $note->note_id]);
            }); ?>
        </div>
        <?php
        $this->foot->render();
    }
}