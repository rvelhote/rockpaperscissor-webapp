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

use AppBundle\Entity\GameType;
use AppBundle\Entity\MoveType;
use AppBundle\Entity\Rule;
use AppBundle\Repository\GameTypeRepository;
use Tests\AppBundle\WithFixturesTestCase;

/**
 * Class GameTypeTest
 * @package Tests\AppBundle\Entity
 */
class GameTypeTest extends WithFixturesTestCase
{
    /**
     * @var GameTypeRepository
     */
    protected $repository;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->repository = $this->manager->getRepository('AppBundle:GameType');
    }

    /**
     * @dataProvider getExpectedData
     */
    public function testRetrieveGameType($expected)
    {
        $rules = $this->manager->getRepository('AppBundle:Rule')->findAll();
        $moves = $this->manager->getRepository('AppBundle:MoveType')->findAll();

        /** @var GameType $gameTypeFromDb */
        $gameTypeFromDb = $this->repository->find($expected[0]);
        $this->assertNotNull($gameTypeFromDb);

        $this->assertEquals($expected[0], $gameTypeFromDb->getId());
        $this->assertEquals($expected[1], $gameTypeFromDb->getName());

        $this->assertCount(3, $gameTypeFromDb->getMoveTypes());
        $this->assertCount(3, $gameTypeFromDb->getRules());

        $this->assertEquals($rules, $gameTypeFromDb->getRules()->toArray());
        $this->assertEquals($moves, $gameTypeFromDb->getMoveTypes()->toArray());
    }

    /**
     * @dataProvider getExpectedData
     */
    public function testRemoveGameType($expected)
    {
        /** @var GameType $gameType */
        $gameType = $this->repository->find($expected[0]);
        $moveTypes = $gameType->getMoveTypes();
        $rules = $gameType->getRules();

        $totalMoveTypes = $gameType->getMoveTypes()->count();
        $totalRules = $gameType->getRules()->count();

        /** @var MoveType $moveType */
        foreach($moveTypes as $moveType) {
            $this->assertTrue($gameType->getMoveTypes()->contains($moveType));
            $gameType->removeMoveType($moveType);

            $this->manager->persist($gameType);
            $this->manager->flush();

            $gameType = $this->repository->find($expected[0]);

            $this->assertEquals(--$totalMoveTypes, $gameType->getMoveTypes()->count());
            $this->assertFalse($gameType->getMoveTypes()->contains($moveType));
        }

        /** @var Rule $moveType */
        foreach($rules as $rule) {
            $this->assertTrue($gameType->getRules()->contains($rule));
            $gameType->removeRule($rule);

            $this->manager->persist($gameType);
            $this->manager->flush();

            $gameType = $this->repository->find($expected[0]);

            $this->assertEquals(--$totalRules, $gameType->getRules()->count());
            $this->assertFalse($gameType->getRules()->contains($rule));
        }

        $this->assertEquals(0, $gameType->getRules()->count());
        $this->assertEquals(0, $gameType->getMoveTypes()->count());
    }

    /**
     * @return array
     */
    public function getExpectedData()
    {
        return [
            [[1, 'Rock Paper Scissors']]
        ];
    }
}
