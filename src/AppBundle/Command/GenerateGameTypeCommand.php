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

use AppBundle\Entity\GameType;
use AppBundle\Entity\MoveType;
use AppBundle\Entity\Rule;
use AppBundle\Repository\MoveTypeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateGameTypeCommand
 * @package AppBundle\Command
 */
class GenerateGameTypeCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $entityManager = null;

    /**
     * Configure the command that will generate game types
     */
    protected function configure()
    {
        $this->setName('rps:generate-game-type');
        $this->setDescription('Generates a new game type. Only supports Rock Paper Scissors for now');
        $this->setHelp('For now there is only one game type. More to come soon.');
    }

    /**
     * Execute the command.
     * It will create a single game type of Rock Paper Scissors and generate the required rules to play it.
     * @param InputInterface $input User's input arguments and other information.
     * @param OutputInterface $output The way to output stuff to the user.
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $output->writeln('');
        $output->writeln('Generating a new game type. Game is <info>Rock Paper Scissors</info>');

        $moves = $this->moves();
        $output->writeln(' > Moves created');

        $gameType = $this->gameType('Rock Paper Scissors', $moves);
        $output->writeln(' > Moves Types created');

        $this->rules($gameType, $moves);
        $output->writeln(' > Rules created');

        $output->writeln('The <info>Rock Paper Scissors</info> game type was created. <comment>Brilliant!!</comment>');
        $output->writeln('');
    }

    /**
     * Insert the moves that are a part of the game we want to generate.
     * TODO Different game types share the same moves (e.g Rock-Paper-Scissors-Lizard-Spock vs Rock-Paper-Scissors).
     *
     * @return MoveType[] The list of move types that were generated.
     */
    private function moves() : array
    {
        $moves = [];

        foreach(['Rock', 'Paper', 'Scissors'] as $move) {
            $moveType = new MoveType();

            $moveType->setName($move);
            $moveType->setSlug(mb_strtolower($move));

            $this->entityManager->persist($moveType);
            $this->entityManager->flush();

            $moves[] = $moveType;
        }

        return $moves;
    }

    /**
     * Build the game type from the moves in the database.
     * @param string $name The name of the game we are creating
     * @param array $moves The list of moves that belong to the game
     * @return GameType A generated game type.
     *
     * TODO This method currently obtains all moves regardless of the game that they belong to.
     * TODO This method should obtain moves from the previous method.
     */
    private function gameType(string $name, array $moves) : GameType
    {
        $gameType = new GameType();
        $gameType->setName($name);

        foreach($moves as $move) {
            $gameType->addMoveType($move);
        }

        $this->entityManager->persist($gameType);
        $this->entityManager->flush();

        return $gameType;
    }

    /**
     * @param GameType $gameType
     * @param MoveType[] $moves
     */
    private function rules(GameType $gameType, array $moves)
    {
        $r1 = new Rule();
        $r1->setGameType($gameType);
        $r1->setWinner($moves[1]);
        $r1->setLoser($moves[0]);
        $r1->setOutcome('Covers');

        $r2 = new Rule();
        $r2->setGameType($gameType);
        $r2->setWinner($moves[2]);
        $r2->setLoser($moves[1]);
        $r2->setOutcome('Cuts');

        $r3 = new Rule();
        $r3->setGameType($gameType);
        $r3->setWinner($moves[0]);
        $r3->setLoser($moves[2]);
        $r3->setOutcome('Smashes');

        $gameType->addRule($r1);
        $gameType->addRule($r2);
        $gameType->addRule($r3);

        $this->entityManager->persist($gameType);
        $this->entityManager->flush();
    }
}
