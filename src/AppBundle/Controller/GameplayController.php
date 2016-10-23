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
use AppBundle\Entity\MoveType;
use AppBundle\Form\MakeMoveForm;
use AppBundle\Service\GameEngine;
use Balwan\RockPaperScissor\Game\Result\Tie;
use DateTime;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class GameplayController
 * @package AppBundle\Controller
 */
class GameplayController extends FOSRestController
{
    /**
     * @Post("/api/v1/play", name="play", options={ "method_prefix" = false })
     * @View(serializerGroups={"Default"})
     */
    public function playAction(Request $request)
    {
        $playerSubmission = new MakeMoveForm();

        $options = ['csrf_protection' => false];
        $playerSubmissionForm = $this->createFormBuilder($playerSubmission, $options)
            ->add('gameset', TextType::class)
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

        /** @var GameSet $gameset */
        $criteria = ['guid' => $playerSubmission->getGameset()];
        $gameset = $this->getDoctrine()->getRepository('AppBundle:GameSet')->findOneBy($criteria);

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


        $gameset->setLastActivity(new DateTime());
        $this->getDoctrine()->getEntityManager()->persist($gameset);
        $this->getDoctrine()->getEntityManager()->flush();

        return [
            'user' => $this->getUser(),
            'gameset' => $gameset,
            'stats' => $this->get('app.service.stats')->getStats(),
        ];
    }

    /**
     * @Get("/api/v1/game", name="game", options={ "method_prefix" = false })
     * @View(serializerGroups={"Default"})
     */
    public function gameAction(Request $request)
    {
        return [
            'user' => $this->getUser(),
            'gameset' => $this->get('app.service.gameset')->findGameset(),
            'stats' => $this->get('app.service.stats')->getStats(),
        ];
    }
}
