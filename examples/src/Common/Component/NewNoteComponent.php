<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Component;

final class NewNoteComponent
{
    public function render(string $submit_path): void
    {
        ?>
        <form class="form-group" method="post" action="<?= $submit_path ?>">
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