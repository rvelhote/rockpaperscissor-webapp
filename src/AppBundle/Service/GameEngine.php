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

use AppBundle\Entity\Result as ResultEntity;
use Balwan\RockPaperScissor\Game\Game;
use Balwan\RockPaperScissor\Game\Result\AbstractGameResult;
use Balwan\RockPaperScissor\Game\Result\Tie;
use Balwan\RockPaperScissor\Move\Move;
use Balwan\RockPaperScissor\Player\Player;
use Balwan\RockPaperScissor\Rule\Rule;
use Balwan\RockPaperScissor\Rule\RuleCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class GameEngine
 * @package AppBundle\Service
 */
class GameEngine
{
    /**
     * @param string $move1
     * @param string $move2
     * @param Collection $rules
     * @return AbstractGameResult
     */
    public function play(string $move1, string $move2, Collection $rules) : AbstractGameResult
    {
        $ruleset = new RuleCollection();

        /** @var \AppBundle\Entity\Rule $r */
        foreach ($rules as $r) {
            $ruleset->add(new Rule($r->getWinner()->getName(), $r->getLoser()->getName(), $r->getOutcome()));
        }

        $gameGame = new Game(new Move($move1), new Move($move2), $ruleset);
        return $gameGame->result();
    }

    public function make()
    {

    }
}
