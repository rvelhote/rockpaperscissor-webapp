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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GeneratePlayersCommand
 * @package AppBundle\Command
 */
class SetupCommand extends ContainerAwareCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('rps:setup');
        $this->setDescription('Clears the database, generates games, moves, game types, players and rules');
        $this->setHelp('Use this command to destroy everything and start over.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dropCommand = $this->getApplication()->find('doctrine:database:drop');
        $dropCommand->run(new ArrayInput(['--force' => true]), $output);

        $createCommand = $this->getApplication()->find('doctrine:database:create');
        $createCommand->run($input, $output);

        $generateCommand = $this->getApplication()->find('doctrine:generate:entities');
        $generateCommand->run(new ArrayInput(['name' => 'AppBundle']), $output);

        $updateCommand = $this->getApplication()->find('doctrine:schema:update');
        $updateCommand->run(new ArrayInput(['--force' => true]), $output);

        $generateGameTypeCommand = $this->getApplication()->find('rps:generate-game-type');
        $generateGameTypeCommand->run($input, $output);

        $generatePlayersCommand = $this->getApplication()->find('rps:generate-players');
        $generatePlayersCommand->run($input, $output);

        $generateGamesCommand = $this->getApplication()->find('rps:generate-games');
        $generateGamesCommand->run($input, $output);
    }
}
