<?php
declare(strict_types=1);

namespace Paket\Fram\Helper;

final class Shell
{
    public static function deleteCoverage(): void
    {
        $dir = __DIR__ . '/../../coverage';
        if (is_dir($dir)) {
            self::rimraf(__DIR__ . '/../../coverage');
        }
    }

    private static function rimraf(string $dir): void
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            if (is_dir("{$dir}/{$file}") && !is_link($dir)) {
                self::rimraf("{$dir}/{$file}");
            } else {
                unlink("{$dir}/{$file}");
            }
        }
        rmdir($dir);
    }
}