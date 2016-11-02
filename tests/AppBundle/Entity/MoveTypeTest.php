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

use AppBundle\DataFixtures\ORM\LoadGameTypeData;
use AppBundle\DataFixtures\ORM\LoadMoveData;
use AppBundle\DataFixtures\ORM\LoadRulesData;
use AppBundle\Entity\GameType;
use AppBundle\Entity\MoveType;
use AppBundle\Entity\Rule;
use AppBundle\Repository\RuleRepository;
use AppKernel;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use FOS\RestBundle\Controller\Annotations\Move;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RuleTest
 * @package Tests\AppBundle\Entity
 */
class MoveTypeTest extends KernelTestCase
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
     *
     */
    public function setUp()
    {
        self::bootKernel();

        $this->manager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $this->manager->getRepository('AppBundle:MoveType');

        $metadatas = $this->manager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->manager);
        $schemaTool->updateSchema($metadatas);

        $loader = new Loader;
        $loader->addFixture(new LoadMoveData());
        $loader->addFixture(new LoadGameTypeData());
        $loader->addFixture(new LoadRulesData());

        $purger = new ORMPurger($this->manager);
        $executor = new ORMExecutor($this->manager, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     * @dataProvider getMoveType
     */
    public function testInsertAndRetrieve($expected)
    {
        /** @var MoveType $moveTypeFromDB */
        $moveTypeFromDB = $this->repository->find($expected[0]);
        $this->assertNotNull($moveTypeFromDB);

        $this->assertEquals($expected[0], $moveTypeFromDB->getId());
        $this->assertEquals($expected[1], $moveTypeFromDB->getName());
        $this->assertEquals($expected[2], $moveTypeFromDB->getSlug());
        $this->assertEquals($expected[3], $moveTypeFromDB->getGameTypes()->count());
    }

    public function getMoveType()
    {
        return [
            [[1, 'Rock', 'rock', 1]],
            [[2, 'Paper', 'paper', 1]],
            [[3, 'Scissors', 'scissors', 1]],
        ];
    }
}
