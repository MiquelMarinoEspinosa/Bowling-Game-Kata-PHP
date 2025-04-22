<?php

declare(strict_types=1);

namespace Tests;

use Exception;
use Kata\Game;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    public function testGivenNegativePinsWhenRollThenThrowAnException(): void
    {
        $this->expectException(Exception::class);

        $game = new Game();
        $game->roll(-1);
    }

    public function testGivenOnePinWhenRollThenReturnsOnePin(): void
    {
        $game = new Game();
        $game->roll(1);

        self::assertSame(1, $game->score());
    }
}
