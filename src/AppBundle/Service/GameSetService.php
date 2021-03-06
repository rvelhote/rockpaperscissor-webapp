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
use AppBundle\Entity\GameSet;
use AppBundle\Entity\Player;
use AppBundle\Repository\GameSetRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class GameSetService
 * @package AppBundle\Service
 */
class GameSetService
{
    /**
     * @var GameSetRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var Player
     */
    private $player;

    /**
     * GameSetService constructor.
     * @param GameSetRepository $repository
     * @param EntityManager $manager
     * @param TokenStorage $token
     */
    public function __construct(GameSetRepository $repository, EntityManager $manager, TokenStorage $token)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->player = $token->getToken()->getUser();
    }

    /**
     * @param string $guid
     * @return null|object
     */
    public function findGamesetByGuid(string $guid)
    {
        return $this->repository->findGamesetByGuid($guid);
    }

    /**
     * @return GameSet
     */
    public function findGameset() : GameSet
    {
        /** @var GameSet $gameset */
        $gameset = $this->repository->findGamesetByPlayer($this->player);

        // FIXME Just a quick fix to get us going quickly. Redo this part and turn it into a query.
        if(!is_null($gameset)) {
            foreach($gameset->getGames() as $game) {
                /** @var Game $game */
                if(is_null($game->getResult())) {
                    return $gameset;
                }
            }
        }

        $gameset = $this->repository->findFreeGameSet();
        $gameset->setOwner($this->player);
        $gameset->setLastActivity(new DateTime());

        $this->manager->persist($gameset);
        $this->manager->flush();

        return $gameset;
    }

    /**
     * Updates a gameset's lastPlayed parameter to a certain date.
     *
     * @param GameSet $gameset The gameset we want to update.
     * @param DateTime $date The date that we want to set the lastPlayed attribute to.
     *
     * @return GameSet The changed gameset with updated parameters.
     */
    public function updateLastPlayed(GameSet $gameset, DateTime $date)
    {
        $gameset->setLastActivity($date);

        $this->manager->persist($gameset);
        $this->manager->flush();

        return $gameset;
    }
}
