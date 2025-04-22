<?php

declare(strict_types=1);

namespace Kata;

use Exception;

final class Game
{
    public function roll(int $pins): void
    {
        if ($pins < 0) {
            throw new Exception('pins paramater cannot be negative');
        }
    }

    public function score(): int 
    {
        return 1;
    }
}
