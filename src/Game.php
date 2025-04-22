<?php

declare(strict_types=1);

namespace Kata;

use Exception;

final class Game
{
    private array $frames = [];
    private array $currentFrame = [
        'roll1' => 0,
        'roll2' => 0,
        'bonus' => 0
    ];

    public function roll(int $pins): void
    {
        $this->validatePins($pins);

        if (0 === $this->currentFrame['roll1']) {
            $this->currentFrame['roll1'] = $pins;

            return;
        }

        if (0 === $this->currentFrame['roll2']) {
            $this->currentFrame['roll2'] = $pins;

            return;
        }
        
        $this->frames[] = $this->currentFrame;
        $this->currentFrame['roll1'] = $pins;
        $this->currentFrame['roll2'] = 0;

        $previousFrame = $this->frames[count($this->frames) - 1];
        
        if (array_sum($previousFrame) === 10) {
            $this->frames[count($this->frames) - 1]['bonus'] += $pins;
            return; 
        }

    }

    public function score(): int 
    {
        $score = array_reduce(
            $this->frames,
            static fn(int $result, array $frame) =>
                $result + $frame['roll1'] + $frame['roll2']+ $frame['bonus'],
            0
        );

        return $score + array_sum($this->currentFrame);
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
