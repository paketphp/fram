<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Simple;

use Paket\Fram\Examples\Common\Component\FootComponent;
use Paket\Fram\Examples\Common\Component\HeadComponent;
use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

class NewNoteView implements HtmlView
{
    public const PATH = '/simple/note/new';
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
        $this->head->render('Simple Notes');
        ?>
        <div class="container">
            <h1>New note</h1>
            <form class="form-group" method="post" action="<?= NewNoteBackend::PATH ?>">
                <div class="form-row">
                    <input class="form-control w-50 mb-2" type="text" name="title" placeholder="title" required>
                </div>
                <div class="form-row">
                    <textarea class="form-control w-50 mb-2" name="text" placeholder="text" required></textarea>
                </div>
                <div class="form-row">
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
        <?php
        $this->foot->render();
    }
}