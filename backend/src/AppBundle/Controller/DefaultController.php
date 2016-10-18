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
use AppBundle\Service\GameSetService;
use AppBundle\Service\PlayerService;
use AppBundle\Service\StatsService;
use Balwan\RockPaperScissor\Game\Result\Tie;
use DateTime;
use Exception;
use FOS\RestBundle\Controller\FOSRestController;
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
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 *
 */
class DefaultController extends FOSRestController
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

        if ($result instanceof Tie) {
            $game->setResult(0);
        } else if ($result->getWinner()->getPlay() === $move->getSlug()) {
            $game->setResult(1);
        } else {
            $game->setResult(2);
        }


        // Update the game definition with the data of the player that played the game
        $game->setPlayer1($this->getUser());
        $game->setMovePlayer1($move);
        $game->setDatePlayed(new DateTime());

        $this->getDoctrine()->getEntityManager()->persist($game);
        $this->getDoctrine()->getEntityManager()->flush();

        /** @var GameService $g */
        $ggg = $this->get('app.service.game');

        $newGame = [
            'game' => $ggg->getGame(),
            'result' => [
                'opponent' => $game->getPlayer2()->getUsername(),
                'move' => $game->getMovePlayer2()->getName(),
                'winner' => (!$result instanceof Tie && $result->getWinner()->getPlay() === $move->getSlug()),
                'tied' => ($result instanceof Tie),
                'outcome' => ($result instanceof Tie) ? 'Tie' : $result->getRule()->getText(),
            ],
            'stats' => ['win' => 0, 'draw' => 0, 'lose' => 0,],
        ];

        return new JsonResponse($newGame);
    }

    /**
     *
     *
     *
     * @Get("/api/v1/game", name="game", options={ "method_prefix" = false })
     *
     * @View(serializerGroups={"Default"})
     *
     *
     */
    public function gameAction(Request $request)
    {
//        /** @var PlayerService $player */
//        $player = $this->get('app.service.player');

        /** @var GameService $g */
//        $game = $this->get('app.service.game');


        return $this->get('app.service.gameset')->findGameset();

        //$gameset = $gamesetService->findGameset();

        //$games = $gameset->getGames();



//            'game' => $gamesetService,
//            'stats' => ['win' => 0, 'draw' => 0, 'lose' => 0,],
//        ]);
    }
}
