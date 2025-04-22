<?php

declare(strict_types=1);

namespace Kata;

use Exception;

final class Game
{
    private array $frames = [];
    private int $currentFrame = 0;

    public function roll(int $pins): void
    {
        $this->validatePins($pins);

        if (!isset($this->frames[$this->currentFrame]['roll1'])) {
            $this->frames[$this->currentFrame]['roll1'] = $pins;

            if (10 === $pins) {
                $this->currentFrame++;
            }

            return;
        }

        if (!isset($this->frames[$this->currentFrame]['roll2'])) {
            $this->frames[$this->currentFrame]['roll2'] = $pins;

            if (0 === $this->currentFrame) {
                return;
            }

            $previousFrame = $this->frames[$this->currentFrame - 1];
            if (array_sum($previousFrame) === 10 && !isset($previousFrame['roll2'])) {
                $this->frames[$this->currentFrame - 1]['bonus'] += array_sum($this->frames[$this->currentFrame]);
                return; 
            }

            return;
        }

        $this->currentFrame++;
        $this->frames[$this->currentFrame]['roll1'] = $pins;
        
        $previousFrame = $this->frames[$this->currentFrame - 1];
        
        if (array_sum($previousFrame) === 10 && isset($previousFrame['roll2'])) {
            $this->frames[$this->currentFrame - 1]['bonus'] += $pins;
            return; 
        }
    }

    public function score(): int 
    {
        $result = 0;
        return array_reduce(
            $this->frames,
            static fn(int $result, array $frame) =>
                $result + $frame['roll1'] + $frame['roll2']+ $frame['bonus'],
            $result
        );
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
