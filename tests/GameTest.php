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
        $numFrames = 10;
        $this->rollMultiple($numFrames);

        $this->game->roll($pins);
    }

    public function testGivenTenthFrameFinishedWithPendingSpareWhenRollOnceAgainThenTheRollShouldBeAllowed(): void
    {
        $numFrames = 9;
        $multiplePinsRolled = $this->rollMultiple($numFrames);
        
        $sparePins = 5;
        $this->game->roll($sparePins);
        $this->game->roll($sparePins);
        
        $this->game->roll($sparePins);

        self::assertSame(
            $multiplePinsRolled + $sparePins * 3,
            $this->game->score()
        );
    }

    public function testGivenTenthFrameFinishedWithPendingSpareWhenRollTwiceAgainThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(Exception::class);

        $numFrames = 9;
        $this->rollMultiple($numFrames);
        
        $sparePins = 5;
        $this->game->roll($sparePins);
        $this->game->roll($sparePins);
        
        $this->game->roll($sparePins);
        $this->game->roll($sparePins);
    }

    public function testGivenTenthFrameFinishedWithPendingStrikeWhenRollOnceAgainThenTheRollShouldBeAllowed(): void
    {
        $numFrames = 9;
        $multiplePinsRolled = $this->rollMultiple($numFrames);
        
        $strikePins = 10;
        $this->game->roll($strikePins);

        $pins = 3;
        $this->game->roll($pins);

        self::assertSame(
            $multiplePinsRolled + $strikePins + $pins,
            $this->game->score()
        );
    }

    public function testGivenTenthFrameFinishedWithPendingStrikeWhenRollTwiceAgainThenTheRollsShouldBeAllowed(): void
    {
        $numFrames = 9;
        $multiplePinsRolled = $this->rollMultiple($numFrames);
        
        $strikePins = 10;
        $this->game->roll($strikePins);

        $pins = 3;
        $this->game->roll($pins);
        $this->game->roll($pins);

        self::assertSame(
            $multiplePinsRolled + $strikePins + $pins * 2,
            $this->game->score()
        );
    }

    public function testGivenTenthFrameFinishedWithPendingStrikeWhenRollThreeTimesAgainThenTheLastRollShouldNotBeAllowed(): void
    {
        $this->expectException(Exception::class);
        
        $numFrames = 9;
        $this->rollMultiple($numFrames);
        
        $strikePins = 10;
        $this->game->roll($strikePins);

        $pins = 3;
        $this->game->roll($pins);
        $this->game->roll($pins);
        $this->game->roll($pins);
    }

    public function testScoringBowlingPdfSample(): void
    {
        $this->game->roll(1);
        $this->game->roll(4);

        $this->game->roll(4);
        $this->game->roll(5);

        $this->game->roll(6);
        $this->game->roll(4);

        $this->game->roll(5);
        $this->game->roll(5);

        $this->game->roll(10);

        $this->game->roll(0);
        $this->game->roll(1);

        $this->game->roll(7);
        $this->game->roll(3);
        
        $this->game->roll(6);
        $this->game->roll(4);

        $this->game->roll(10);

        $this->game->roll(2);
        $this->game->roll(8);
        $this->game->roll(6);

        self::assertSame(133, $this->game->score());
    }

    private function rollMultiple(int $numFrames): int
    {
        $pins = 3;
        $numRollsPerFrame = 2;
        for($currentRoll = 0; $currentRoll < $numFrames*$numRollsPerFrame; $currentRoll++) {
            $this->game->roll($pins);
        }

        return $this->game->score();
    } 
}
