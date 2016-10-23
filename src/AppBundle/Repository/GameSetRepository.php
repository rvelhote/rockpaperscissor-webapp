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
namespace AppBundle\Repository;

use AppBundle\Entity\Player;
use Doctrine\ORM\EntityRepository;

/**
 * Class GameSetRepository
 * @package AppBundle\Repository
 */
class GameSetRepository extends EntityRepository
{
    /**
     * Find a free gameset to play. Essentially this will find a gameset that doesn't have the 'locked' flag set.
     * @return null|object A gameset (which allows access to all the games) or null if nothing is found.
     */
    public function findFreeGameSet()
    {
        $criteria = ['owner' => null];
        return $this->findOneBy($criteria);
    }

    /**
     * @param Player $player
     * @return null|object
     */
    public function findGamesetByPlayer(Player $player) {
        $criteria = ['owner' => $player->getId()];
        $orderBy = ['lastActivity' => 'DESC'];
        return $this->findOneBy($criteria, $orderBy);
    }
}
