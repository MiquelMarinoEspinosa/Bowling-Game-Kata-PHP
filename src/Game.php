<?php

declare(strict_types=1);

namespace Kata;

use Exception;

final class Game
{
    public function roll(int $pins): void
    {
        throw new Exception('pins paramater cannot be negative');
    }
}
