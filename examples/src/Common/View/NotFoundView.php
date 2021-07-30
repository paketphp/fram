<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\View;

use Paket\Fram\Examples\Common\Component\FootComponent;
use Paket\Fram\Examples\Common\Component\HeadComponent;
use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

final class NotFoundView implements HtmlView
{
    /** @var HeadComponent */
    private $head;
    /** @var FootComponent */
    private $foot;

    public function __construct(HeadComponent $head, FootComponent $foot)
    {
        $this->head = $head;
        $this->foot = $foot;
    }

    public function render(Route $route)
    {
        http_response_code(404);
        $this->head->render('404 - Error');
        ?>
        <div class="container">
            <h1>404 - Error</h1>
            <p>No such route</p>
        </div>
        <?php
        $this->foot->render();
    }
}