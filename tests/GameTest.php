<?php

declare(strict_types=1);

namespace Tests;

use Exception;
use Kata\Game;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    private Game $game;

    protected function setUp(): void
    {
        $this->game = new Game();
    }

    public function testGivenNegativePinsWhenRollThenThrowAnException(): void
    {
        $this->expectException(Exception::class);

        $this->game->roll(-1);
    }

    public function testGivenOnePinWhenRollThenReturnsOnePin(): void
    {
        $this->game->roll(1);

        self::assertSame(1, $this->game->score());
    }
}
