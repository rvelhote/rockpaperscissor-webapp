<?php
/**
 * MIT License
 *
 * Copyright (c) 2016 Ricardo Velhote
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Tests\AppBundle\Service;

use AppBundle\Entity\GameType;
use AppBundle\Entity\MoveType;
use AppBundle\Entity\Rule;
use AppBundle\Service\GameEngine;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

/**
 * Class GameEngineTest
 * @package Tests\AppBundle\Service
 */
class GameEngineTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GameEngine
     */
    private $service;

    /**
     * In the tests where this class variable is used it will be copied to another variable to have the correct type.
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $movePlayer1;

    /**
     * In the tests where this class variable is used it will be copied to another variable to have the correct type.
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $movePlayer2;

    /**
     * In the tests where this class variable is used it will be copied to another variable to have the correct type.
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $gameType;

    /**
     *
     */
    public function setUp()
    {
        $this->service = new GameEngine();

        $this->movePlayer1 = $this->getMockBuilder(MoveType::class)->disableOriginalConstructor()->getMock();
        $this->movePlayer2 = $this->getMockBuilder(MoveType::class)->disableOriginalConstructor()->getMock();

        $this->gameType = $this->getMockBuilder(GameType::class)->disableOriginalConstructor()->getMock();
        $this->gameType->expects($this->once())->method('getRules')->willReturn($this->getRules());
    }

    /**
     * @test Confirm that the result will end up in a tie
     */
    public function testPlayTie()
    {
        $this->movePlayer1->expects($this->atLeastOnce())->method('getSlug')->willReturn('rock');
        $this->movePlayer2->expects($this->atLeastOnce())->method('getSlug')->willReturn('rock');

        /** @var MoveType $move1 */
        $move1 = $this->movePlayer1;

        /** @var MoveType $move2 */
        $move2 = $this->movePlayer2;

        /** @var GameType $gameType */
        $gameType = $this->gameType;

        $result = $this->service->play($move1, $move2, $gameType);
        $this->assertEquals(0, $result);
    }

    /**
     * @test Confirm that the result of this play will have player1 as the winner
     */
    public function testPlayWinnerPlayer1()
    {
        $this->movePlayer1->expects($this->atLeastOnce())->method('getSlug')->willReturn('paper');
        $this->movePlayer2->expects($this->atLeastOnce())->method('getSlug')->willReturn('rock');

        /** @var MoveType $move1 */
        $move1 = $this->movePlayer1;

        /** @var MoveType $move2 */
        $move2 = $this->movePlayer2;

        /** @var GameType $gameType */
        $gameType = $this->gameType;

        $result = $this->service->play($move1, $move2, $gameType);
        $this->assertEquals(1, $result);
    }

    /**
     * @test Confirm that the result of this play will have player2 as the winner
     */
    public function testPlayWinnerPlayer2()
    {
        $this->movePlayer1->expects($this->atLeastOnce())->method('getSlug')->willReturn('scissors');
        $this->movePlayer2->expects($this->atLeastOnce())->method('getSlug')->willReturn('rock');

        /** @var MoveType $move1 */
        $move1 = $this->movePlayer1;

        /** @var MoveType $move2 */
        $move2 = $this->movePlayer2;

        /** @var GameType $gameType */
        $gameType = $this->gameType;

        $result = $this->service->play($move1, $move2, $gameType);
        $this->assertEquals(2, $result);
    }

    /**
     * @return array
     */
    public function getRules()
    {
        $rules = [];
        $moves = [];

        foreach(['Rock', 'Paper', 'Scissors'] as $move) {
            $moveType = new MoveType();

            $moveType->setName($move);
            $moveType->setSlug(mb_strtolower($move));

            $moves[] = $moveType;
        }

        $rule = new Rule();
        $rule->setWinner($moves[1]);
        $rule->setLoser($moves[0]);
        $rule->setOutcome('Covers');
        $rules[] = $rule;

        $rule = new Rule();
        $rule->setWinner($moves[2]);
        $rule->setLoser($moves[1]);
        $rule->setOutcome('Cuts');
        $rules[] = $rule;

        $rule = new Rule();
        $rule->setWinner($moves[0]);
        $rule->setLoser($moves[2]);
        $rule->setOutcome('Smashes');
        $rules[] = $rule;

        return $rules;
    }
}
