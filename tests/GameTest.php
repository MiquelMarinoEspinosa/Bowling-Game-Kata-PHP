<?php

declare(strict_types=1);

namespace Tests;

use Kata\Game;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    public function testAssertTrue(): void
    {
        $game = new Game();
        self::assertInstanceOf(Game::class, $game);
    }
}
