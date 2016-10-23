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

use AppBundle\Entity\Game;
use AppBundle\Entity\MoveType;
use AppBundle\Entity\Player;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\PlayerRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class PlayerService
 * @package AppBundle\Service
 */
class PlayerService
{
    /**
     * @var GameRepository
     */
    private $repository;

    /**
     * @var StatsService
     */
    private $stats;

    /**
     * @var Player
     */
    private $player;

    /**
     * @var Session
     */
    private $session;

    /**
     * PlayerService constructor.
     * @param PlayerRepository $repository
     * @param StatsService $stats
     * @param Session $session
     * @internal param EntityManager $manager
     */
    public function __construct(PlayerRepository $repository, StatsService $stats, Session $session)
    {
        $this->repository = $repository;
        $this->stats = $stats;
        $this->session = $session;
        $this->player = $repository->find(1);
    }

    /**
     * @return bool
     */
    public function isAuthenticated() : bool
    {
        return !is_null($this->session->get('player'));
    }

    /**
     * @return array
     */
    public function statistics() : array
    {
        return $this->stats->all($this->player);
    }
}
