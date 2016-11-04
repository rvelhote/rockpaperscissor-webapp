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
namespace AppBundle\Service;

use AppBundle\Entity\GameType;
use AppBundle\Entity\MoveType;
use Welhott\RockPaperScissor\Game\Game;
use Welhott\RockPaperScissor\Game\Result\Tie;
use Welhott\RockPaperScissor\Game\Result\Win;
use Welhott\RockPaperScissor\Move\Move;
use Welhott\RockPaperScissor\Rule\Rule;
use Welhott\RockPaperScissor\Rule\RuleCollection;

/**
 * Class GameEngine
 * @package AppBundle\Service
 */
class GameEngine
{
    /**
     * @param MoveType $move1
     * @param MoveType $move2
     * @param GameType $gameType
     * @return int
     */
    public function play(MoveType $move1, MoveType $move2, GameType $gameType) : int
    {
        $ruleset = new RuleCollection();

        /** @var \AppBundle\Entity\Rule $r */
        foreach ($gameType->getRules() as $r) {
            $ruleset->add(new Rule($r->getWinner()->getName(), $r->getLoser()->getName(), $r->getOutcome()));
        }

        $gameGame = new Game(new Move($move1->getSlug()), new Move($move2->getSlug()), $ruleset);

        /** @var Win $result */
        $result = $gameGame->result();

        if($result instanceof Tie) {
            return 0;
        }

        if($result->getWinner()->getPlay() === $move1->getSlug()) {
            return 1;
        }

        return 2;
    }
}
