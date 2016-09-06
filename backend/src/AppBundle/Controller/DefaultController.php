<?php

namespace AppBundle\Controller;

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
        $moves = ['Rock', 'Paper', 'Scissors'];

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
                'move' => $moves[random_int(0, 2)],
                'winner' => mt_rand(0, 2),
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
        $moves = ['Rock', 'Paper', 'Scissors'];

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
