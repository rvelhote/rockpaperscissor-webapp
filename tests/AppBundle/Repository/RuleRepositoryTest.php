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
use AppBundle\Repository\RuleRepository;
use AppKernel;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RuleTest
 * @package Tests\AppBundle\Entity
 */
class RuleRepositoryTest extends KernelTestCase
{
    /**
     * @var RuleRepository
     */
    protected $repository;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * Setup the test for the Rules Repository
     */
    public function setUp()
    {
        self::bootKernel();
        $this->manager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository('AppBundle:Rule');
    }

    /**
     *
     */
    public function testTotalRulesInDatabase()
    {
        $rules = $this->repository->findAll();
        $this->assertCount(3, $rules);
    }

    /**
     * @test Make sure all the Rock Paper Scissors rules are correct in the database
     * @dataProvider getExpectedRules
     */
    public function testGetRulesProperties($expected)
    {
        $rules = $this->repository->findAll();

        /** @var Rule $rule */
        foreach($rules as $index => $rule) {
            $this->assertEquals($expected[$index]['id'], $rule->getId());
            $this->assertEquals($expected[$index]['winner'], $rule->getWinner()->getName());
            $this->assertEquals($expected[$index]['loser'], $rule->getLoser()->getName());
            $this->assertEquals($expected[$index]['outcome'], $rule->getOutcome());
            $this->assertEquals($expected[$index]['gameType'], $rule->getGameType()->getName());
        }
    }

    /**
     * @return array
     */
    public function getExpectedRules()
    {
        $rule1 = [
            'id' => 1,
            'winner' => 'Paper',
            'loser' => 'Rock',
            'outcome' => 'Covers',
            'gameType' => 'Rock Paper Scissors'
        ];

        $rule2 = [
            'id' => 2,
            'winner' => 'Scissors',
            'loser' => 'Paper',
            'outcome' => 'Cuts',
            'gameType' => 'Rock Paper Scissors'
        ];

        $rule3 = [
            'id' => 3,
            'winner' => 'Rock',
            'loser' => 'Scissors',
            'outcome' => 'Smashes',
            'gameType' => 'Rock Paper Scissors'
        ];

        return [[[$rule1, $rule2, $rule3]]];
    }
}
