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
namespace AppBundle\Command;

use AppBundle\Entity\Game;
use AppBundle\Entity\GameSet;
use AppBundle\Entity\GameType;
use AppBundle\Entity\MoveType;
use AppBundle\Entity\Player;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateUserCommand
 * @package AppBundle\Command
 */
class GenerateGamesCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $entityManager = null;

    /**
     *
     */
    protected function configure()
    {
        $this->setName('rps:generate-games');
        $this->setDescription('Generates new RPS games.');
        $this->setHelp('Generate random games for testing purposes...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var Player[] $players */
        $players = $this->entityManager->getRepository('AppBundle:Player')->findAll();

        /** @var MoveType[] $moves */
        $moves = $this->entityManager->getRepository('AppBundle:MoveType')->findAll();

        /** @var GameType[] $gameTypes */
        $gameTypes = $this->entityManager->getRepository('AppBundle:GameType')->findAll();

        for ($i = 0; $i < 33; $i++) {
            $gameset = new GameSet();
            $gameset->setGuid(Uuid::uuid4()->toString());
            $gameset->setLocked(false);

            for($j = 0; $j < 3; $j++) {
                $game = new Game();
                $game->setGuid(Uuid::uuid4()->toString());

                $game->setPlayer2($players[random_int(1, count($players) - 1)]);
                $game->setMovePlayer2($moves[random_int(0, 2)]);
                $game->setGameType($gameTypes[0]);
                $game->setGameSet($gameset);

                $gameset->addGame($game);
            }

            $this->entityManager->persist($gameset);
            $this->entityManager->flush();
        }

        $output->writeln(sprintf('<info>%d</info> games were created!', 33));
    }
}
