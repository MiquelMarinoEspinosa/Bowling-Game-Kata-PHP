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

        $this->processFrame($pins);
    }

    public function score(): int 
    {
        $result = 0;
        return array_reduce(
            $this->frames,
            static fn(int $result, array $frame): int =>
                $result + $frame['rollOne'] + $frame['rollTwo']+ $frame['bonus'],
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

    private function processFrame(int $pins): void
    {
        if (!isset($this->frames[$this->currentFrame]['rollOne'])) {
            $this->processRollOne($pins);

            return;
        }

        $this->processRollTwo($pins);
    }

    private function processRollOne(int $pins): void
    {
        $this->frames[$this->currentFrame]['rollOne'] = $pins;
        
        if (10 === $pins) {
            $this->currentFrame++;
        }

        if (0 === $this->currentFrame) {
            return;
        }

        $this->processSpare();
    }

    private function processRollTwo(int $pins): void
    {
        $this->frames[$this->currentFrame]['rollTwo'] = $pins;

        if (0 === $this->currentFrame) {
            $this->currentFrame++;
            return;
        }

        $this->processStrike();

        $this->currentFrame++;
    }

    private function processSpare(): void
    {
        $previousFrame = $this->frames[$this->currentFrame - 1];
        if (array_sum($previousFrame) === 10 && isset($previousFrame['rollTwo'])) {
            $this->frames[$this->currentFrame - 1]['bonus'] = $this->frames[$this->currentFrame]['rollOne']; 
        }
    }

    private function processStrike(): void
    {
        $previousFrame = $this->frames[$this->currentFrame - 1];
        if (array_sum($previousFrame) === 10 && !isset($previousFrame['rollTwo'])) {
            $this->frames[$this->currentFrame - 1]['bonus'] = array_sum($this->frames[$this->currentFrame]);
        }
    }
}
