<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Component;

use Iterator;
use Paket\Fram\Examples\Common\Note\Note;
use Paket\Fram\Util\Escape;

final class NoteListComponent
{
    /**
     * @param Iterator|Note[] $notes
     */
    public function render(Iterator $notes, callable $href): void
    {
        ?>
        <ul class="list-group-flush">
            <?php foreach ($notes as $note) : ?>
                <li class="list-group-item">
                    <a href="<?= $href($note) ?>">
                        <h2><?= Escape::html($note->title) ?></h2></a>
                    <p><?= Escape::html($note->text) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }
}