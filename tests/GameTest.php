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
        
        $game = new Game()->roll(-1);
    }
}
