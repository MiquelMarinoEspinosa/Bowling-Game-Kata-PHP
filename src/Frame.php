<?php

declare(strict_types=1);

namespace Kata;

use Exception;

final class Frame
{
    private const int MINIMUM_PINS = 0;
    private const int MAXIMUM_PINS = 10;

    private ?int $firstRoll = null;
    private ?int $secondRoll = null;
    private ?int $bonus = null;

    public function isSpare(): bool
    {
        return $this->firstRoll + $this->secondRoll === 10 && null !== $this->secondRoll;
    }

    public function isStrike(): bool
    {
        return $this->firstRoll + $this->secondRoll === 10 && null === $this->secondRoll;
    }

    public function rollScore(): int
    {
        return $this->firstRoll + $this->secondRoll;
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

        $frame->bonus = $this->firstRoll;

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

    public function isFirstRoll(): bool
    {
        return null === $this->firstRoll;
    }

    public function processFirstRoll(int $pins): self
    {
        $this->validatePins($pins);
        $this->firstRoll = $pins;

        return $this;
    }

    public function processSecondRoll(int $pins): self
    {
        $this->validatePins($pins);
        $this->secondRoll = $pins;

        return $this;
    }

    private function validatePins(int $pins): void
    {
        if ($pins < self::MINIMUM_PINS) {
            throw new Exception('pins paramater cannot be lesser than 0');
        }

        if ($pins > self::MAXIMUM_PINS) {
            throw new Exception('pins paramater cannot be greater than 10');
        }
    }
}
