<?php

declare(strict_types=1);

namespace Kata;

use Exception;

final class Game
{
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

    private function processFrame(int $pins): void
    {
        if ($this->currentFrame()->isFirstRoll() === true) {
            $this->processFirstRoll($pins);

            return;
        }

        $this->processSecondRoll($pins);
    }

    private function processFirstRoll(int $pins): void
    {
        $this->updateCurrentFrame(
            $this->currentFrame()->processFirstRoll($pins)
        );
        
        if ($this->currentFrame()->isStrike()) {
            $this->generateNextFrame();
        }

        if ($this->isCurrentFrameTheFirst() === true) {
            return;
        }

        $this->processSpare();

        $this->processStrike();
    }

    private function processSecondRoll(int $pins): void
    {
        $this->updateCurrentFrame(
            $this->currentFrame()->processSecondRoll($pins)
        );

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
        if (null !== $this->lastExtraFrame) {
            return $this->frames[$this->currentFrame];    
        }
        return $this->frames[$this->currentFrame - 1];
    }

    private function currentFrame(): Frame
    {
        if (null !== $this->lastExtraFrame) {
            return $this->lastExtraFrame;
        }
        return $this->frames[$this->currentFrame];
    }

    private function generateNextFrame(): void 
    {
        if (null !== $this->lastExtraFrame) {
            return;
        }
        $this->currentFrame++;
        $this->generateCurrentFrame();
        if ($this->currentFrame >= self::LAST_FRAME) {
            $this->lastExtraFrame = $this->currentFrame();
            unset($this->frames[$this->currentFrame]);
            $this->currentFrame--;
        }
    }

    private function generateCurrentFrame(): void
    {
        $this->frames[$this->currentFrame] = new Frame();
    }

    private function updatePreviousFrame(Frame $frame): void
    {
        if (null !== $this->lastExtraFrame) {
            $this->frames[$this->currentFrame] = $frame;
            return;
        }
        $this->frames[$this->currentFrame - 1] = $frame;
    }

    private function updateCurrentFrame(Frame $frame): void
    {
        if (null !== $this->lastExtraFrame) {
            $this->lastExtraFrame = $frame;
            return;
        }
        $this->frames[$this->currentFrame] = $frame;
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

        if ($this->previousFrame()->isSpare() === false && $this->previousFrame()->isStrike() === false) {
            return false;
        }

        if ($this->previousFrame()->isSpare() === true && $this->lastExtraFrame->isFirstRoll() === true) {
            return true;
        }

        if ($this->previousFrame()->isStrike() === true && $this->lastExtraFrame->isFirstRoll() === true) {
            return true;
        }

        return $this->previousFrame()->isStrike() === true && $this->lastExtraFrame->isSecondRoll() === true;
    }
}
