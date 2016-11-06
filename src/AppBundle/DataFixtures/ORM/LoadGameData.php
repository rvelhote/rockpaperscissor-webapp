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
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Game;
use AppBundle\Entity\GameSet;
use AppBundle\Entity\GameType;
use AppBundle\Entity\MoveType;
use AppBundle\Entity\Player;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadGameData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadGameData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var Player[] $players */
        $players = $this->manager->getRepository('AppBundle:Player')->findAll();

        /** @var MoveType[] $moves */
        $moves = $this->manager->getRepository('AppBundle:MoveType')->findAll();

        /** @var GameType[] $gameTypes */
        $gameTypes = $this->manager->getRepository('AppBundle:GameType')->findAll();

        for ($i = 0; $i < 33; $i++) {
            $gameset = new GameSet();
            $gameset->setGuid(Uuid::uuid4()->toString());

            for($j = 0; $j < 3; $j++) {
                $game = new Game();
                $game->setGuid(Uuid::uuid4()->toString());

                $game->setPlayer2($players[random_int(1, count($players) - 1)]);
                $game->setMovePlayer2($moves[random_int(0, 2)]);
                $game->setGameType($gameTypes[0]);
                $game->setGameSet($gameset);

                $gameset->addGame($game);
            }

            $this->manager->persist($gameset);
            $this->manager->flush();
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * Sets the container.
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->manager = $this->container->get('doctrine.orm.entity_manager');
    }
}
