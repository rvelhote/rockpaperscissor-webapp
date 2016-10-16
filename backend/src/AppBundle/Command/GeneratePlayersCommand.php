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

use AppBundle\Entity\Player;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GeneratePlayersCommand
 * @package AppBundle\Command
 */
class GeneratePlayersCommand extends ContainerAwareCommand
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
        $this->setName('rps:generate-players');
        $this->setDescription('Generates a bunch of players to to use to assign to games.');
        $this->setHelp('This command will only generate the players. It does not allow you to create a single one.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $player = $this->createMainPlayer();
        $output->writeln(sprintf('Main player created with the handle <info>%s</info>', $player->getUsername()));

        $players = $this->createPlayers();
        $output->writeln(sprintf('<info>%d</info> other players were created', count($players)));
    }

    /**
     * Creates the main player. This exists just to ensure that the main player had the ID 1
     * @return Player The player that was created.
     * TODO Create session management.
     */
    private function createMainPlayer() : Player
    {
        $encoder = $this->getContainer()->get('security.password_encoder');

        $player = new Player();
        $player->setUsername('@rvelhote');
        $player->setIsActive(true);
        $player->setPassword($encoder->encodePassword($player, 'x'));

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return $player;
    }

    /**
     * Creates a number of random players.
     * @param int $count The amount of players to create
     * @return Player[] The list of created players
     */
    private function createPlayers(int $count = 20) : array
    {
        $encoder = $this->getContainer()->get('security.password_encoder');

        /** @var Player[] $players */
        $players = [];

        for ($i = 0; $i < $count; $i++) {
            $players[$i] = new Player();
            $players[$i]->setUsername('@abardadyn <'.$i.'>');
            $players[$i]->setIsActive(true);
            $players[$i]->setPassword($encoder->encodePassword($players[$i], 'x'));

            $this->entityManager->persist($players[$i]);
            $this->entityManager->flush();
        }

        return $players;
    }
}
