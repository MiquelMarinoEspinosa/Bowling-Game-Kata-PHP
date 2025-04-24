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

        $this->updateCurrentFrame(
            $this->currentFrame()->roll($pins)
        );

        $this->processBonuss();

        $this->generateNextFrame();
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

    private function updateCurrentFrame(Frame $frame): void
    {
        if ($this->isLastExtraFrame() === true) {
            $this->lastExtraFrame = $frame;
            return;
        }
        $this->frames[$this->currentFrame] = $frame;
    }

    private function processBonuss(): void
    {
        if ($this->isCurrentFrameTheFirst() === true) {
            return;
        }

        $this->updatePreviousFrame(
            $this->currentFrame()->processSpare(
                $this->previousFrame()
            )
        );

        $this->updatePreviousFrame(
            $this->currentFrame()->processStrike(
                $this->previousFrame()
            )
        );
    }

    private function previousFrame(): Frame
    {
        if ($this->isLastExtraFrame() === true) {
            return $this->frames[$this->currentFrame];    
        }
        return $this->frames[$this->currentFrame - 1];
    }

    private function currentFrame(): Frame
    {
        if ($this->isLastExtraFrame() === true) {
            return $this->lastExtraFrame;
        }
        return $this->frames[$this->currentFrame];
    }

    private function generateNextFrame(): void 
    {
        if ($this->isLastExtraFrame() === true) {
            return;
        }

        if ($this->currentFrame()->isSecondRoll() === true) {
            if ($this->currentFrame()->isStrike() === false) {
                return;
            }
        }
        
        $this->currentFrame++;
        $this->generateCurrentFrame();
        $this->saveLastExtraFrame();
    }

    private function saveLastExtraFrame(): void
    {
        if ($this->currentFrame < self::LAST_FRAME) {
            return;
        }

        $this->lastExtraFrame = $this->currentFrame();
        unset($this->frames[$this->currentFrame]);
        $this->currentFrame--;
    }

    private function generateCurrentFrame(): void
    {
        $this->frames[$this->currentFrame] = new Frame();
    }

    private function updatePreviousFrame(Frame $frame): void
    {
        if ($this->isLastExtraFrame() === true) {
            $this->frames[$this->currentFrame] = $frame;
            return;
        }
        $this->frames[$this->currentFrame - 1] = $frame;
    }

    private function isCurrentFrameTheFirst(): bool
    {
        return 0 === $this->currentFrame;
    }

    private function isRolleAllowed(): bool
    {
        if ($this->isLastExtraFrame() === false) {
            return true;
        }

        if ($this->previousFrame()->isSpare() === false && $this->previousFrame()->isStrike() === false) {
            return false;
        }

        if ($this->previousFrame()->isSpare() === true) {
            return $this->lastExtraFrame->isFirstRoll() === true;
        }

        if ($this->lastExtraFrame->isFirstRoll() === true) {
            return true;
        }

        return $this->lastExtraFrame->isSecondRoll() === true;
    }

    private function isLastExtraFrame(): bool
    {
        return null !== $this->lastExtraFrame;
    }
}
