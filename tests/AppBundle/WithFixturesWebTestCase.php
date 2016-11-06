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
namespace Tests\AppBundle;

use AppBundle\DataFixtures\ORM\LoadGameData;
use AppBundle\DataFixtures\ORM\LoadGameTypeData;
use AppBundle\DataFixtures\ORM\LoadMoveData;
use AppBundle\DataFixtures\ORM\LoadPlayersData;
use AppBundle\DataFixtures\ORM\LoadRulesData;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class WithFixturesTestCase
 * @package Tests\AppBundle
 */
class WithFixturesWebTestCase extends AuthenticatedWebTestCase
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var ORMPurger
     */
    protected $purger;

    /**
     * @var ORMExecutor
     */
    protected $executor;

    /**
     *
     */
    public function setUp()
    {
        self::bootKernel();

        $this->manager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $metadatas = $this->manager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->manager);
        $schemaTool->updateSchema($metadatas);

        $loader = new Loader();
        $loader->addFixture(new LoadMoveData());
        $loader->addFixture(new LoadGameTypeData());
        $loader->addFixture(new LoadRulesData());

        $playerFixture = new LoadPlayersData();
        $playerFixture->setContainer(static::$kernel->getContainer());
        $loader->addFixture($playerFixture);

        $gameFixture = new LoadGameData();
        $gameFixture->setContainer(static::$kernel->getContainer());
        $loader->addFixture($gameFixture);

        $this->purger = new ORMPurger($this->manager);
        $this->executor = new ORMExecutor($this->manager, $this->purger);
        $this->executor->execute($loader->getFixtures());

        parent::setUp();
    }
}