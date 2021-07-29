<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Simple;

use Paket\Fram\Examples\Common\Component\FootComponent;
use Paket\Fram\Examples\Common\Component\HeadComponent;
use Paket\Fram\Examples\Common\Component\NoteListComponent;
use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

final class IndexView implements HtmlView
{
    public const PATH = '/simple/';
    /** @var HeadComponent */
    private $head;
    /** @var FootComponent */
    private $foot;
    /** @var NoteListComponent */
    private $noteList;

    public function __construct(HeadComponent $head, FootComponent $foot, NoteListComponent $noteList)
    {
        $this->head = $head;
        $this->foot = $foot;
        $this->noteList = $noteList;
    }

    public function render(Route $route)
    {
        $notesRepository = new NoteRepository();
        $this->head->render('Simple Notes');
        ?>
        <div class="container">
            <h1>Simple Notes</h1>
            <a href="<?= NewNoteView::PATH ?>">New note</a>
            <?php $this->noteList->render($notesRepository->getAllNotes()); ?>
        </div>
        <?php
        $this->foot->render();
    }
}