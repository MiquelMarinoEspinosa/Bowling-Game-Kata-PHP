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
        $pins = -1;
        $this->expectException(Exception::class);

        $this->game->roll($pins);
    }

    public function testGivenOnePinWhenRollThenReturnsOnePin(): void
    {
        $pins = 1;
        $this->game->roll($pins);

        self::assertSame($pins, $this->game->score());
    }

    public function testGivenTenPinsWhenRollThenThrowAnException(): void
    {
        $pins = 11;
        $this->expectException(Exception::class);

        $this->game->roll($pins);
    }

    public function testGivenTwoPinsWhenRollThenReturnsTwoPins(): void
    {
        $pins = 2;
        $this->game->roll($pins);

        self::assertSame($pins, $this->game->score());
    }
}
