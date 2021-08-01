<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Component;

use Paket\Fram\Examples\Common\Util\CsrfTokenService;

final class NewNoteComponent
{
    /** @var CsrfTokenService */
    private $csrfTokenService;

    public function __construct(CsrfTokenService $csrfTokenService)
    {
        $this->csrfTokenService = $csrfTokenService;
    }

    public function render(string $submit_path): void
    {
        ?>
        <form class="form-group" method="post" action="<?= $submit_path ?>">
            <input type="hidden" name="token" value="<?= $this->csrfTokenService->generate() ?>">
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
        <?php
    }
}