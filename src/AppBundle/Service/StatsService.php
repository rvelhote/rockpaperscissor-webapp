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
namespace AppBundle\Service;

use AppBundle\Entity\Player;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\GameSetRepository;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class StatsService.
 * Handles statistics collection for the frontend. Should be used after every request for most up-to-date results.
 * @package AppBundle\Service
 */
class StatsService
{
    /**
     * @var GameRepository Doctrine repository object to access the 'result' table.
     */
    private $repository;

    /**
     * @var Player
     */
    private $player;

    /**
     * StatsService constructor.
     * @param GameRepository $repository
     * @param TokenStorage $token
     */
    public function __construct(GameRepository $repository, TokenStorage $token)
    {
        $this->repository = $repository;
        $this->player = $token->getToken()->getUser();
    }

    /**
     *
     */
    public function getGameWins()
    {
        $query = $this->repository->createQueryBuilder('g');
        $query->select($query->expr()->count('g.id'));

        $query->where('g.player1 = :pid AND g.result = :win1');
        $query->orWhere('g.player2 = :pid AND g.result = :win2');

        $query->setParameter(':pid', $this->player->getId(), IntegerType::INTEGER);
        $query->setParameter(':win1', 1);
        $query->setParameter(':win2', 2);

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getGameLosses()
    {
        $query = $this->repository->createQueryBuilder('g');
        $query->select($query->expr()->count('g.id'));

        $query->where('g.player1 = :pid AND g.result = :loss1');
        $query->orWhere('g.player2 = :pid AND g.result = :loss2');

        $query->setParameter(':pid', $this->player->getId(), IntegerType::INTEGER);
        $query->setParameter(':loss1', 2);
        $query->setParameter(':loss2', 1);

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getGameDraws()
    {
        $query = $this->repository->createQueryBuilder('g');
        $query->select($query->expr()->count('g.id'));

        $query->where('g.player1 = :pid AND g.result = :draw1');
        $query->orWhere('g.player2 = :pid AND g.result = :draw2');

        $query->setParameter(':pid', $this->player->getId(), IntegerType::INTEGER);
        $query->setParameter(':draw1', 0);
        $query->setParameter(':draw2', 0);

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getStats()
    {
        return [
            'wins' => $this->getGameWins(),
            'losses' => $this->getGameLosses(),
            'draws' => $this->getGameDraws(),
        ];
    }
}
