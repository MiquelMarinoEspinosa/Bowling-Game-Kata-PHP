<?php

declare(strict_types=1);

namespace Kata;

final class Frame
{
    public ?int $rollOne = null;
    public ?int $rollTwo = null;
    private ?int $bonus = null;

    public function isSpare(): bool
    {
        return $this->rollOne + $this->rollTwo === 10 && null !== $this->rollTwo;
    }

    public function isStrike(): bool
    {
        return $this->rollOne + $this->rollTwo === 10 && null === $this->rollTwo;
    }

    public function rollScore(): int
    {
        return $this->rollOne + $this->rollTwo;
    }

    public function totalScore(): int
    {
        return $this->rollScore() + $this->bonus;
    }

    public function processSpare(self $frame): self
    {
        if ($frame->isSpare() === false) {
            return $frame; 
        }

        $frame->bonus = $this->rollOne;

        return $frame;
    }

    public function processStrike(self $frame): self
    {
        if ($frame->isStrike() === false) {
            return $frame;
        }

        $frame->bonus = $this->rollScore();

        return $frame;
    }
}
