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
namespace AppBundle\Controller;

use AppBundle\Entity\GameType;
use AppBundle\Entity\Move;
use AppBundle\Entity\MoveType;
use AppBundle\Entity\Result;
use Balwan\RockPaperScissor\Game\Game;
use Balwan\RockPaperScissor\Game\Result\Tie;
use Balwan\RockPaperScissor\Game\Result\Win;
use Balwan\RockPaperScissor\Player\Player;
use Balwan\RockPaperScissor\Rule\Rule;
use Balwan\RockPaperScissor\Rule\RuleCollection;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     *
     * @Route("/generate", name="generate")
     */
    public function generateAction()
    {
        $em = $this->getDoctrine()->getManager();

        $moves = ['Rock', 'Paper', 'Scissors'];
        foreach($moves as $move) {
            $moveType = new MoveType();
            $moveType->setName($move);
            $moveType->setSlug(mb_strtolower($move));
            $em->persist($moveType);
            $em->flush();
        }

        $player1 = new \AppBundle\Entity\Player();
        $player1->setHandle("@rvelhote");

        $em->persist($player1);
        $em->flush();

        for($i = 0; $i < 20; $i++) {
            $player = new \AppBundle\Entity\Player();
            $player->setHandle("@abardadyn <".$i.">");

            $em->persist($player);
            $em->flush();
        }

        $player1 = $this->getDoctrine()->getRepository('AppBundle:Player')->find(1);
        $players = $this->getDoctrine()->getRepository('AppBundle:Player')->findAll();

        /** @var MoveType[] $moves */
        $moves = $this->getDoctrine()->getRepository('AppBundle:MoveType')->findAll();

        $gameType = new GameType();
        $gameType->setName('Rock Paper Scissors');
        $gameType->addMoveType($moves[0]);
        $gameType->addMoveType($moves[1]);
        $gameType->addMoveType($moves[2]);

        $em->persist($gameType);
        $em->flush();

        $rules = new \AppBundle\Entity\Rule();
        $rules->setGameType($gameType);
        $rules->setWinner($moves[1]);
        $rules->setLoser($moves[0]);
        $rules->setOutcome('Covers');
        $em->persist($rules);
        $em->flush();

        $rules = new \AppBundle\Entity\Rule();
        $rules->setGameType($gameType);
        $rules->setWinner($moves[2]);
        $rules->setLoser($moves[1]);
        $rules->setOutcome('Cuts');
        $em->persist($rules);
        $em->flush();

        $rules = new \AppBundle\Entity\Rule();
        $rules->setGameType($gameType);
        $rules->setWinner($moves[0]);
        $rules->setLoser($moves[2]);
        $rules->setOutcome('Smashes');
        $em->persist($rules);
        $em->flush();


//        var_dump($moves[0]->getName());exit;

        for($i = 0; $i < 1000; $i++) {
            $unique = Uuid::uuid4()->toString();

            $game = new \AppBundle\Entity\Game();
            $game->setGuid($unique);

            $game->setPlayer2($players[random_int(1, count($players) - 1)]);
            $game->setMovePlayer2($moves[random_int(0, 2)]);
            $game->setGameType($gameType);

            $em->persist($game);
            $em->flush();
        }


        return new Response("Cool");
    }

    /**
     *
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        /** @var \AppBundle\Entity\Game $game */
//        $game = $this->getDoctrine()->getRepository('AppBundle:Game')->findOneBy(['datePlayed' => null]);


//        $repository = $this->getDoctrine()->getRepository('AppBundle:Game');
//
//        $product = $repository->find(1);
//
//        var_dump($product->getGameType());exit;


//        $game = new \AppBundle\Entity\Game();
//        $game->setUuid(uniqid());
//        $game->setGameType(55);
//        $game->setDateCreated(new \DateTime());
//
//
////
////        $game = new GameType();
////        $game->setName('Rock Paper Scissors');
////        $game->setDescription('RPS');
////        $game->setIsActive(true);
////
////
//////        $moveType = new MoveType();
//////        $moveType->setName('Scissors');
//////        $moveType->setSlug('scissors');
//////        $moveType->setIsActive(true);
//////        $moveType->setDateCreated(new \DateTime());
//////
//        $em = $this->getDoctrine()->getManager();
//
//        // tells Doctrine you want to (eventually) save the Product (no queries yet)
//        $em->persist($game);
//
//        // actually executes the queries (i.e. the INSERT query)
//        $em->flush();

        return new Response('');
    }

    /**
     *
     * @Route("/play", name="play")
     *
     * @param Request $request
     * @return JsonResponse
     *
     * TODO Validate if the move that was sent is correct for this game
     * TODO Validate if the move actually exists
     * TODO Validate that the game exists and is playable
     * TODO The database has to contain the rules so we can build and feed the RuleCollection
     */
    public function playAction(Request $request)
    {

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

//        $em->getConnection()->beginTransaction(); // suspend auto-commit
//        try {

            /** @var \AppBundle\Entity\Game $game */
            $game = $this->getDoctrine()->getRepository('AppBundle:Game')->findOneBy(['guid' => $request->get('game')]);

            /** @var \AppBundle\Entity\Player $player */
            $player = $this->getDoctrine()->getRepository('AppBundle:Player')->find(1);

            /** @var MoveType $playerMove */
            $playerMove = $this->getDoctrine()->getRepository('AppBundle:MoveType')->findOneBy(['slug' => $request->get('move')]);

            if (is_null($game)) {
                die('Oh nooo ;)');
            }

            $player1 = new Player($player->getHandle(), $playerMove->getSlug());
            $player2 = new Player($game->getPlayer2()->getHandle(), $game->getMovePlayer2()->getSlug());

            $rules = new RuleCollection();
//        $rules->add(new Rule('Paper', 'Rock', 'Covers'));
//        $rules->add(new Rule('Scissors', 'Paper', 'Cuts'));
//        $rules->add(new Rule('Rock', 'Scissors', 'Smashes'));

            $databaseRules = $game->getGameType()->getRules();

            /** @var \AppBundle\Entity\Rule $r */
            foreach ($databaseRules as $r) {
                $rules->add(new Rule($r->getWinner()->getName(), $r->getLoser()->getName(), $r->getOutcome()));
            }

            $gameGame = new Game($player1, $player2, $rules);
            $gameResult = $gameGame->result();

            $game->setPlayer1($player);
            $game->setMovePlayer1($playerMove);
            $game->setDatePlayed(new \DateTime());

            $r1 = new Result();
            $r1->setPlayer($game->getPlayer1());

            $r2 = new Result();
            $r2->setPlayer($game->getPlayer2());

            if ($gameResult instanceof Tie) {
                $r1->setDraw(true);
                $r2->setDraw(true);
            } else {
                if ($gameResult->getWinner() == $player1) {
                    $r1->setWin(true);
                    $r2->setLose(true);
                } else {
                    $r1->setLose(true);
                    $r2->setWin(true);
                }
            }

            $game->addResult($r1);
            $game->addResult($r2);

//            $em->persist($r1);
//            $em->persist($r2);
            $em->persist($game);
            $em->flush();

            $resultsRepo = $this->getDoctrine()->getRepository('AppBundle:Result');





//            $em->getConnection()->commit();
//        } catch (\Exception $e) {
//            $em->getConnection()->rollBack();
//            throw $e;
//        }


        $newGame = [
            'game' => $this->getNewGame(),
            'result' => [
                'opponent' => $game->getPlayer2()->getHandle(),
                'move' => $player2->getPlay(),
                'winner' => ($gameResult instanceof Tie) ? 0 : ($gameResult->getWinner() == $player1 ? 1 : 2),
                'outcome' => ($gameResult instanceof Tie) ? 'Tie' : $gameResult->getRule()->getText(),
            ],
            'stats' => [
                'win' => count($resultsRepo->findBy(['player' => $game->getPlayer1()->getId(), 'win' => 1])),
                'draw' => count($resultsRepo->findBy(['player' => $game->getPlayer1()->getId(), 'draw' => 1])),
                'lose' => count($resultsRepo->findBy(['player' => $game->getPlayer1()->getId(), 'lose' => 1])),
            ]
        ];


        return new JsonResponse($newGame);
    }

    /**
     *
     * @Route("/game", name="game")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function gameAction(Request $request)
    {
        $resultsRepo = $this->getDoctrine()->getRepository('AppBundle:Result');

        /** @var \AppBundle\Entity\Player $player */
        $player = $this->getDoctrine()->getRepository('AppBundle:Player')->find(1);

        $newGame = [
            'game' => $this->getNewGame(),
            'stats' => [
                'win' => count($resultsRepo->findBy(['player' => $player->getId(), 'win' => 1])),
                'draw' => count($resultsRepo->findBy(['player' => $player->getId(), 'draw' => 1])),
                'lose' => count($resultsRepo->findBy(['player' => $player->getId(), 'lose' => 1])),
            ]
        ];

        return new JsonResponse($newGame);
    }

    /**
     * TODO Investigate SELECT FOR UPDATE
     * TODO CRON that will search for games in a locked state for a long time. Requires lock timestamp.
     */
    private function getNewGame()
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \AppBundle\Entity\Game $game */
        $game = $this->getDoctrine()->getRepository('AppBundle:Game')->findOneBy(['locked' =>  null]);
        $game->setLocked(true);
        $em->persist($game);
        $em->flush();


//        /** @var \AppBundle\Entity\Game $game */
//        $game = $this->getDoctrine()->getRepository('AppBundle:Game')->findOneBy(['datePlayed' => null]);

        if(is_null($game)) {
            $game = [
                'guid' => 'game over',
                'opponent' => [
                    'handle' => 'game over',
                    'picture' => ''
                ],
                'moves' => [],
                'gameType' => ['name' => 'game over']
            ];
            return $game;
        }

//        /** @var \AppBundle\Entity\Game $entity */
//        $entity = $em->find('AppBundle\Entity\Game', $game->getId(), \Doctrine\DBAL\LockMode::PESSIMISTIC_WRITE);
//
//        $entity->setLocked(true);
//
//        $em->persist($entity);

        $moves = array_map(function($move) {
            /** @var MoveType $move */
            return ['name' => $move->getName(), 'move' => $move->getSlug()];
        }, $game->getGameType()->getMoveTypes()->toArray());

        $game = [
            'guid' => $game->getGuid(),
            'opponent' => [
                'handle' => $game->getPlayer2()->getHandle(),
                'picture' => ''
            ],
            'moves' => $moves,
            'gameType' => ['name' => $game->getGameType()->getName()]
        ];

        return $game;
    }
}
