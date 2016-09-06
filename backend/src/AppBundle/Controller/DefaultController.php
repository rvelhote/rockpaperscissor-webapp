<?php

namespace AppBundle\Controller;

use Balwan\RockPaperScissor\Game\Game;
use Balwan\RockPaperScissor\Game\Result\Tie;
use Balwan\RockPaperScissor\Game\Result\Win;
use Balwan\RockPaperScissor\Player\Player;
use Balwan\RockPaperScissor\Rule\Rule;
use Balwan\RockPaperScissor\Rule\RuleCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     *
     * @Route("/play", name="play")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function playAction(Request $request)
    {
        $moves = [
            ['name' => 'Rock', 'move' => 'rock'],
            ['name' => 'Paper', 'move' => 'paper'],
            ['name' => 'Scissors', 'move' => 'scissors'],
        ];

        $player1 = new Player('@rvelhote', $request->get('move', null));
        $player2 = new Player('@'.uniqid(), $moves[random_int(0, 2)]['move']);

        $rules = new RuleCollection();
        $rules->add(new Rule('Paper', 'Rock', 'Covers'));
        $rules->add(new Rule('Scissors', 'Paper', 'Cuts'));
        $rules->add(new Rule('Rock', 'Scissors', 'Smashes'));

        $game = new Game($player1, $player2, $rules);
        $gameResult = $game->result();

        $game = [
            'game' => [
                'uuid' => uniqid('game', true),
                'opponent' => [
                    'handle' => "@".uniqid('handle', true),
                    'picture' => ''
                ],
                'moves' => $moves,
                'gameType' => ['name' => 'Rock Paper Scissors']
            ],
            'result' => [
                'opponent' => "@".uniqid('handle', true),
                'move' => $player2->getPlay(),
                'winner' => ($gameResult instanceof Tie) ? 0 : ($gameResult->getWinner() == $player1 ? 1 : 2),
                'outcome' => ($gameResult instanceof Tie) ? 'Tie' : $gameResult->getRule()->getText(),
            ],
        ];

        return new JsonResponse($game);
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
        $moves = [
            ['name' => 'Rock', 'move' => 'rock'],
            ['name' => 'Paper', 'move' => 'paper'],
            ['name' => 'Scissors', 'move' => 'scissors'],
        ];

        $game = [
            'uuid' => uniqid('game', true),
            'opponent' => [
                'handle' => "@".uniqid('handle', true),
                'picture' => ''
            ],
            'moves' => $moves,
            'gameType' => ['name' => 'Rock Paper Scissors']
        ];

        $headers = [
            'Access-Control-Allow-Origin' => 'http://localhost:8000',
            'Access-Control-Allow-Headers' => 'Access-Control-Allow-Origin',
        ];

        return new JsonResponse($game, 200, $headers);
    }
}
