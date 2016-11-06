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
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Rule;
use AppBundle\Repository\RuleRepository;
use Tests\AppBundle\WithFixturesKernelTestCase;

/**
 * Class RuleTest
 * @package Tests\AppBundle\Entity
 */
class RuleKernelTest extends WithFixturesKernelTestCase
{
    /**
     * @var RuleRepository
     */
    protected $repository;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->repository = $this->manager->getRepository('AppBundle:Rule');
    }

    /**
     * @dataProvider getMoveType
     */
    public function testInsertAndRetrieve($expected)
    {
        /** @var Rule $rule */
        $rule = $this->repository->find($expected[0]);
        $this->assertNotNull($rule);

        $this->assertEquals($expected[0], $rule->getId());
        $this->assertEquals($expected[1], $rule->getWinner()->getId());
        $this->assertEquals($expected[2], $rule->getLoser()->getId());
        $this->assertEquals($expected[3], $rule->getGameType()->getId());
        $this->assertEquals($expected[4], $rule->getOutcome());

        $gameType = $this->manager->getRepository('AppBundle:GameType')->find($rule->getGameType()->getId());
        $moveTypeWinner = $this->manager->getRepository('AppBundle:MoveType')->find($rule->getWinner()->getId());
        $moveTypeLoser = $this->manager->getRepository('AppBundle:MoveType')->find($rule->getLoser()->getId());

        $this->assertEquals($gameType, $rule->getGameType());
        $this->assertEquals($moveTypeWinner, $rule->getWinner());
        $this->assertEquals($moveTypeLoser, $rule->getLoser());
    }

    /**
     * @return array
     */
    public function getMoveType()
    {
        return [
            [[1, 2, 1, 1, 'Covers']],
            [[2, 3, 2, 1, 'Cuts']],
            [[3, 1, 3, 1, 'Smashes']]
        ];
    }
}
