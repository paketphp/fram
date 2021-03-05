<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Simple;

use Paket\Fram\Examples\Common\Component\FootComponent;
use Paket\Fram\Examples\Common\Component\HeadComponent;
use Paket\Fram\Examples\Common\Note\NoteRepository;
use Paket\Fram\Util\Escape;
use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

final class IndexView implements HtmlView
{
    public const PATH = '/simple/';
    /** @var HeadComponent */
    private $head;
    /** @var FootComponent */
    private $foot;

    public function __construct()
    {
        $this->head = new HeadComponent();
        $this->foot = new FootComponent();
    }

    public function render(Route $route)
    {
        $notesRepository = new NoteRepository();
        $this->head->render('Simple Notes');
        ?>
        <div class="container">
            <h1>Simple Notes</h1>
            <a href="<?= NewNoteView::PATH ?>">New note</a>
            <ul class="list-group-flush">
                <?php foreach ($notesRepository->getAllNotes() as $note) : ?>
                    <li class="list-group-item">
                        <h2><?= Escape::html($note->title) ?></h2>
                        <p><?= Escape::html($note->text) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        $this->foot->render();
    }
}