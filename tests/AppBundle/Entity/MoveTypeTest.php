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
use AppBundle\Repository\RuleRepository;
use Tests\AppBundle\WithFixturesTestCase;

/**
 * Class RuleTest
 * @package Tests\AppBundle\Entity
 */
class MoveTypeTest extends WithFixturesTestCase
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
        $this->repository = $this->manager->getRepository('AppBundle:MoveType');
    }

    /**
     * @dataProvider getMoveType
     */
    public function testInsertAndRetrieve($expected)
    {
        /** @var MoveType $move */
        $move = $this->repository->find($expected[0]);
        $this->assertNotNull($move);

        $this->assertEquals($expected[0], $move->getId());
        $this->assertEquals($expected[1], $move->getName());
        $this->assertEquals($expected[2], $move->getSlug());
        $this->assertEquals($expected[3], $move->getGameTypes()->count());

        $gameTypes = $this->manager->getRepository('AppBundle:GameType')->findAll();
        $this->assertEquals($gameTypes, $move->getGameTypes()->toArray());
    }

    /**
     * @dataProvider getMoveType
     */
    public function testRemoveGameType($expected)
    {
        /** @var MoveType $move */
        $move = $this->repository->find($expected[0]);
        $this->assertNotNull($move);

        $moveGameTypes = $move->getGameTypes();
        $totalMoveGameTypes = $move->getGameTypes()->count();

        /** @var GameType $moveGameType */
        foreach($moveGameTypes as $moveGameType) {
            $this->assertTrue($move->getGameTypes()->contains($moveGameType));
            $move->removeGameType($moveGameType);

            $this->manager->persist($move);
            $this->manager->flush();

            $move = $this->repository->find($expected[0]);

            $this->assertEquals(--$totalMoveGameTypes, $move->getGameTypes()->count());
            $this->assertFalse($move->getGameTypes()->contains($moveGameType));
        }
    }

    /**
     *
     */
    public function testAddGameType()
    {
        $gameType = new GameType();
        $gameType->setName('Test New Game Type');

        $moveType = new MoveType();
        $moveType->setName('Test New Move');
        $moveType->setSlug(mb_strtolower($moveType->getName()));
        $moveType->addGameType($gameType);

        $this->manager->persist($moveType);
        $this->manager->flush();

        /** @var MoveType $moveTypeFromDb */
        $moveTypeFromDb = $this->repository->findOneBy(['slug' => $moveType->getSlug()]);
        $this->assertNotNull($moveTypeFromDb);
    }

    /**
     * @return array
     */
    public function getMoveType()
    {
        return [
            [[1, 'Rock', 'rock', 1]],
            [[2, 'Paper', 'paper', 1]],
            [[3, 'Scissors', 'scissors', 1]],
        ];
    }
}
