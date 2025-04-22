<?php

declare(strict_types=1);

namespace Kata;

use Exception;

final class Game
{
    public function roll(int $pins): void
    {
        if ($pins < 0) {
            throw new Exception('pins paramater cannot be lesser than 0');
        }

        if ($pins > 10) {
            throw new Exception('pins paramater cannot be greater than 10');
        }
    }

    public function score(): int 
    {
        return 1;
    }
}
