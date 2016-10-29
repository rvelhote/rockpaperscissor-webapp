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
use Symfony\Component\Form\FormError;
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
     *
     * TODO Investigate 1: instantiate the $gameset, $game and $move by using form types
     * TODO Investigate 2: instantiate the $gameset, $game and $move by passing the Request to the services
     */
    public function playAction(Request $request)
    {
        $parameters = new MakeMoveForm();

        $form = $this->createForm(MakeMoveForm::class, $parameters);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            $callback = function(FormError $error) {
                return $error->getMessage();
            };

            $data = [
                'errors' => array_map($callback, iterator_to_array($form->getErrors(true)))
            ];

            return $this->view($data, 403);
        }

        /** @var GameSet $gameset */
        $gameset = $this->get('app.service.gameset')->findGamesetByGuid($parameters->getGameset());

        /** @var Game $game */
        $game = $this->get('app.service.game')->findGameByGuid($parameters->getGame());

        /** @var MoveType $move */
        $move = $this->get('app.service.move_type')->findMoveBySlug($parameters->getMove());

        /** @var GameEngine $engine */
        $engine = $this->get('app.game_engine');
        $result = $engine->play($move, $game->getMovePlayer2(), $game->getGameType());

        $game->setResult($result);

        // Update the game definition with the data of the player that played the game
        $game->setPlayer1($this->getUser());
        $game->setMovePlayer1($move);
        $game->setDatePlayed(new DateTime());

        $this->getDoctrine()->getManager()->persist($game);
        $this->getDoctrine()->getManager()->flush();


        $gameset->setLastActivity(new DateTime());
        $this->getDoctrine()->getManager()->persist($gameset);
        $this->getDoctrine()->getManager()->flush();

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
    public function gameAction()
    {
        return [
            'user' => $this->getUser(),
            'gameset' => $this->get('app.service.gameset')->findGameset(),
            'stats' => $this->get('app.service.stats')->getStats(),
        ];
    }
}
