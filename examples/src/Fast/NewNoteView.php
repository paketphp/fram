<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Fast;

use Paket\Fram\Examples\Common\Component\FootComponent;
use Paket\Fram\Examples\Common\Component\HeadComponent;
use Paket\Fram\Examples\Common\Component\NewNoteComponent;
use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

final class NewNoteView implements HtmlView
{
    public const PATH = '/fast/note';
    /** @var HeadComponent */
    private $head;
    /** @var FootComponent */
    private $foot;
    /** @var NewNoteComponent */
    private $newNote;

    public function __construct(HeadComponent $head, FootComponent $foot, NewNoteComponent $newNote)
    {
        $this->head = $head;
        $this->foot = $foot;
        $this->newNote = $newNote;
    }

    public function render(Route $route)
    {
        $this->head->render('Fast Notes');
        ?>
        <div class="container">
            <h1>New note</h1>
            <?php $this->newNote->render(NewNoteBackend::PATH); ?>
        </div>
        <?php
        $this->foot->render();
    }
}