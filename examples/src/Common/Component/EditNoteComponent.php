<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Component;

use Paket\Fram\Examples\Common\Note\Note;
use Paket\Fram\Examples\Common\Util\CsrfTokenService;
use Paket\Fram\Util\Escape;

final class EditNoteComponent
{
    /** @var CsrfTokenService */
    private $csrfTokenService;

    public function __construct(CsrfTokenService $csrfTokenService)
    {
        $this->csrfTokenService = $csrfTokenService;
    }

    public function render(Note $note, callable $submit, callable $delete, bool $hidden_id): void
    {
        ?>
        <div class="form-group">
            <form method="post" action="<?= $submit($note) ?>">
                <input type="hidden" name="token" value="<?= $this->csrfTokenService->generate() ?>">
                <?php if ($hidden_id): ?>
                    <input type="hidden" name="note_id" value="<?= $note->note_id ?>">
                <?php endif; ?>
                <div class="form-row">
                    <input class="form-control w-50 mb-2" type="text" name="title" placeholder="title"
                           value="<?= Escape::html($note->title) ?>" required>
                </div>
                <div class="form-row">
                <textarea class="form-control w-50 mb-2" name="text" placeholder="text"
                          required><?= Escape::html($note->text) ?></textarea>
                </div>
                <div class="form-row">
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
            <form class="form-row" method="post" action="<?= $delete($note) ?>">
                <input type="hidden" name="token" value="<?= $this->csrfTokenService->generate() ?>">
                <input type="hidden" name="note_id" value="<?= $note->note_id ?>">
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
        </div>
        <?php
    }
}