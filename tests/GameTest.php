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

    public function testGivenOnePinWhenRollThenScoreReturnsOnePin(): void
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

    public function testGivenTwoPinsWhenRollThenScoreReturnsTwoPins(): void
    {
        $pins = 2;
        $this->game->roll($pins);

        self::assertSame($pins, $this->game->score());
    }

    public function testGivenSpareTenPinsRolledInTwoRollsThenScoreReturnsTenPinsPlusPinsThirdRollTwice(): void
    {
        $pinsFirstRoll = 7;
        $pinsSecondRoll = 3;
        $pinsThirdRoll = 3;
        $expectedSparePins = 16;

        $this->game->roll($pinsFirstRoll);
        $this->game->roll($pinsSecondRoll);
        $this->game->roll($pinsThirdRoll);

        self::assertSame($expectedSparePins, $this->game->score());
    }

    public function testGivenFirstFrameSparedWhenRollThenScoreReturnsSparePinsPlusSecondFramePinsRolled(): void
    {
        $pinsFirstRoll = 7;
        $pinsSecondRoll = 3;
        $pinsThirdRoll = 3;
        $pinsFourthRoll = 3;
        $expectedSparePins = 19;

        $this->game->roll($pinsFirstRoll);
        $this->game->roll($pinsSecondRoll);
        $this->game->roll($pinsThirdRoll);
        $this->game->roll($pinsFourthRoll);

        self::assertSame($expectedSparePins, $this->game->score());
    }

    public function testGivenFirstStrikeWhenRollTwiceMoreThenScoreReturnsStrikePlusNextTwoPinRollsPlusCurrentScore(): void
    {
        $pinsFirstRoll = 10;
        $pinsSecondRoll = 3;
        $pinsThirdRoll = 3;
        $expectedSparePins = 22;

        $this->game->roll($pinsFirstRoll);
        $this->game->roll($pinsSecondRoll);
        $this->game->roll($pinsThirdRoll);

        self::assertSame($expectedSparePins, $this->game->score());
    }

    public function testGivenTenthFrameFinishedWhenRollOnceAgainThenThrowsAnException(): void
    {
        $this->expectException(Exception::class);

        $pins = 3;
        for($roll=0; $roll < 10*2; $roll++) {
            $this->game->roll($pins);
        }

        $this->game->roll($pins);
    }
}
