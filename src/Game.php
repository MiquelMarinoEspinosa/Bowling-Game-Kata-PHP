<?php

declare(strict_types=1);

namespace Kata;

use Exception;

final class Game
{
    private int $pinsRolled = 0;

    public function roll(int $pins): void
    {
        $this->validatePins($pins);

        $this->pinsRolled += $pins;
    }

    public function score(): int 
    {
        return $this->pinsRolled;
    }

    private function validatePins(int $pins): void
    {
        if ($pins < 0) {
            throw new Exception('pins paramater cannot be lesser than 0');
        }

        if ($pins > 10) {
            throw new Exception('pins paramater cannot be greater than 10');
        }
    }
}
