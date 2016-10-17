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

use AppBundle\Entity\Game;
use AppBundle\Entity\GameSet;
use AppBundle\Entity\Player;
use AppBundle\Entity\Result as ResultEntity;
use AppBundle\Repository\GameRepository;
use AppBundle\Service\GameService;
use AppBundle\Service\PlayerService;
use AppBundle\Service\StatsService;
use Balwan\RockPaperScissor\Game\Result\Tie;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use AppBundle\Entity\GameType;
use AppBundle\Entity\MoveType;
use AppBundle\Form\MakeMoveForm;
use AppBundle\Service\GameEngine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 *
 */
class DefaultController extends Controller
{
    /**
     * @Method({"GET"})
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return new Response('Forbidden... for now :)', 403);
    }

    /**
     *
     * @Method({"POST"})
     * @Route("/api/v1/play", name="play")
     *
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function playAction(Request $request)
    {
        $playerSubmission = new MakeMoveForm();

        $options = ['csrf_protection' => false];
        $playerSubmissionForm = $this->createFormBuilder($playerSubmission, $options)
            ->add('game', TextType::class)
            ->add('move', TextType::class)
            ->getForm();

        $playerSubmissionForm->handleRequest($request);

        if (!$playerSubmissionForm->isValid()) {
            var_dump($playerSubmissionForm->getErrors(true)->count());
            foreach ($playerSubmissionForm->getErrors(true) as $e) {
                var_dump($e->getMessage());
            }
            exit;
        }

        /** @var MakeMoveForm $playerSubmission */
        $playerSubmission = $playerSubmissionForm->getData();

//        /** @var Player $player */
//        // TODO Maintain session state instead of hardcoding!
//        $player = $this->getDoctrine()->getRepository('AppBundle:Player')->find(1);

        /** @var Game $game */
        $criteria = ['guid' => $playerSubmission->getGame()];
        $game = $this->getDoctrine()->getRepository('AppBundle:Game')->findOneBy($criteria);

        /** @var MoveType $move */
        $criteria = ['slug' => $playerSubmission->getMove()];
        $move = $this->getDoctrine()->getRepository('AppBundle:MoveType')->findOneBy($criteria);

        /** @var GameEngine $engine */
        $engine = $this->get('app.game_engine');
        $result = $engine->play($move->getSlug(), $game->getMovePlayer2()->getSlug(), $game->getGameType()->getRules());




        // Update the game definition with the data of the player that played the game
//        $game->setPlayer1($player);
//        $game->setMovePlayer1($move);
//        $game->setDatePlayed(new DateTime());
//
//        if()

//        $r1 = new ResultEntity();
//        $r1->setPlayer($game->getPlayer1());
//
//        $r2 = new ResultEntity();
//        $r2->setPlayer($game->getPlayer2());
//
//        if ($result instanceof Tie) {
//            $r1->setDraw(true);
//            $r2->setDraw(true);
//        } else {
//            if ($result->getWinner()->getPlay() === $move->getSlug()) {
//                $r1->setWin(true);
//                $r2->setLose(true);
//            } else {
//                $r1->setLose(true);
//                $r2->setWin(true);
//            }
//        }
//
//        $game->addResult($r1);
//        $game->addResult($r2);

        $this->getDoctrine()->getEntityManager()->persist($game);
        $this->getDoctrine()->getEntityManager()->flush();

//        $newGame = [
//            'game' => $this->getNewGame(),
//            'result' => [
//                'opponent' => $game->getPlayer2()->getHandle(),
//                'move' => $game->getMovePlayer2()->getName(),
//                'winner' => (!$result instanceof Tie && $result->getWinner()->getPlay() === $move->getSlug()),
//                'tied' => ($result instanceof Tie),
//                'outcome' => ($result instanceof Tie) ? 'Tie' : $result->getRule()->getText(),
//            ],
//            'stats' => $this->getStats($player->getId()),
//        ];

        return new JsonResponse(true);
    }

    /**
     * This action will obtain a new game to play and also refresh the user's play stats.
     * @param Request $request
     * @return JsonResponse
     *
     * @Method({"POST"})
     * @Route("/api/v1/game", name="game")
     *
     * TODO Validate if the user if currently logged-in
     */
    public function gameAction(Request $request)
    {
        /** @var GameSet $set */
        $set = $this->getDoctrine()->getRepository('AppBundle:GameSet')->findAll()[0];


        var_dump($set->getGuid());
        /** @var Game $g */
        foreach($set->getGames() as $g) {
            print $g->getGuid()." -- ".$g->getGameType()->getName()." === ";
        }

        exit;

        /** @var PlayerService $player */
        $player = $this->get('app.service.player');

        /** @var GameService $g */
        $game = $this->get('app.service.game');

        return new JsonResponse([
            'game' => $game->getGame(),
            'stats' => $player->statistics(),
        ]);
    }
}
