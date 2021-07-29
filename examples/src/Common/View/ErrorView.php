<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\View;

use Paket\Fram\Examples\Common\Component\FootComponent;
use Paket\Fram\Examples\Common\Component\HeadComponent;
use Paket\Fram\Router\Route;
use Paket\Fram\Util\Escape;
use Paket\Fram\View\HtmlView;

final class ErrorView implements HtmlView
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
        [$status, $message] = $route->getPayload();
        $status = is_int($status) ? $status : 400;
        $message = is_string($message) ? $message : 'Something went wrong';
        $title = "{$status} - Error";

        http_response_code($status);
        $this->head->render($title);
        ?>
        <div class="container">
            <h1><?= $title ?></h1>
            <p><?= Escape::html($message) ?></p>
        </div>
        <?php
        $this->foot->render();
    }
}