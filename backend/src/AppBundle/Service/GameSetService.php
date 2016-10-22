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

use AppBundle\Entity\GameSet;
use AppBundle\Repository\GameSetRepository;
use Doctrine\ORM\EntityManager;

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
     * GameSetService constructor.
     * @param GameSetRepository $repository
     * @param EntityManager $manager
     */
    public function __construct(GameSetRepository $repository, EntityManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @return null|object
     * TODO Console task that clears stable locked games.
     */
    public function findGameset()
    {
        /** @var GameSet $gameset */
        $gameset = $this->repository->findFreeGameSet();
        $gameset->setLocked(true);

        $this->manager->persist($gameset);
        $this->manager->flush();

        return $gameset;
    }
}
