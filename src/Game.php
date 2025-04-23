<?php

declare(strict_types=1);

namespace Kata;

use Exception;

final class Game
{
    private const int MINIMUM_PINS = 0;
    private const int MAXIMUM_PINS = 10;
    private const int LAST_FRAME = 10;

    /** 
     * @var array<Frame>
     */
    private array $frames;
    private int $currentFrame;
    private ?Frame $lastExtraFrame;

    public function __construct()
    {
        $this->frames = [];
        $this->currentFrame = 0;
        $this->lastExtraFrame = null;

        $this->generateCurrentFrame();
    }

    public function roll(int $pins): void
    {
        $this->validatePins($pins);
        if ($this->isRolleAllowed() === false) {
            throw new Exception('cannot roll further than 10th frame');
        }

        $this->processFrame($pins);
    }

    public function score(): int 
    {
        $result = 0;
        return array_reduce(
            $this->frames,
            static fn(int $result, Frame $frame): int => $result + $frame->totalScore(),
            $result
        );
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

    private function processFrame(int $pins): void
    {
        if (!isset($this->currentFrame()->rollOne)) {
            $this->processRollOne($pins);

            return;
        }

        $this->processRollTwo($pins);
    }

    private function processRollOne(int $pins): void
    {
        $this->frames[$this->currentFrame]->rollOne = $pins;
        
        if (self::MAXIMUM_PINS === $pins) {
            $this->generateNextFrame();
        }

        if ($this->isCurrentFrameTheFirst() === true) {
            return;
        }

        $this->processSpare();
    }

    private function processRollTwo(int $pins): void
    {
        $this->currentFrame()->rollTwo = $pins;

        if ($this->isCurrentFrameTheFirst() === true) {
            $this->generateNextFrame();

            return;
        }

        $this->processStrike();

        $this->generateNextFrame();
    }

    private function processSpare(): void
    {
        $this->updatePreviousFrame(
            $this->currentFrame()->processSpare(
                $this->previousFrame()
            )
        );

        if ($this->currentFrame >= self::LAST_FRAME) {
            $this->lastExtraFrame = $this->frames[$this->currentFrame];
            unset($this->frames[$this->currentFrame]);
            $this->currentFrame--;
        }
    }

    private function processStrike(): void
    {
        $this->updatePreviousFrame(
            $this->currentFrame()->processStrike(
                $this->previousFrame()
            )
        );
    }

    private function previousFrame(): Frame
    {
        return $this->frames[$this->currentFrame - 1];
    }

    private function currentFrame(): Frame
    {
        return $this->frames[$this->currentFrame];
    }

    private function generateNextFrame(): void 
    {
        $this->currentFrame++;
        $this->generateCurrentFrame();
        if ($this->currentFrame >= self::LAST_FRAME) {
            $this->lastExtraFrame = $this->currentFrame();
        }
    }

    private function generateCurrentFrame(): void
    {
        $this->frames[$this->currentFrame] = new Frame();
    }

    private function updatePreviousFrame(Frame $frame): void
    {
        $this->frames[$this->currentFrame - 1] = $frame;
    }

    private function isCurrentFrameTheFirst(): bool
    {
        return 0 === $this->currentFrame;
    }

    private function isRolleAllowed(): bool
    {
        if (null === $this->lastExtraFrame) {
            return true;
        }

        return $this->previousFrame()->isSpare() === true && null === $this->lastExtraFrame->rollOne;
    }
}
