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
use AppBundle\Repository\GameRepository;
use Doctrine\ORM\EntityManager;

/**
 * Class GameService
 * @package AppBundle\Service
 */
class GameService
{
    /**
     * @var GameRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var Game
     */
    private $game;

    /**
     * GameService constructor.
     * @param GameRepository $repository
     */
    public function __construct(GameRepository $repository, EntityManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @param string $guid
     * @return null|object
     */
    public function findGameByGuid(string $guid)
    {
        return $this->repository->findByGuid($guid);
    }
}
