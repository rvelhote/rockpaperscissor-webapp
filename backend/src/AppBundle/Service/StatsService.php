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
use AppBundle\Repository\ResultRepository;
use Doctrine\DBAL\Types\Type;
use Exception;

/**
 * Class StatsService.
 * Handles statistics collection for the frontend. Should be used after every request for most up-to-date results.
 * @package AppBundle\Service
 */
class StatsService
{
    /**
     *
     */
    const WIN = 'win';

    /**
     *
     */
    const LOSE = 'lose';

    /**
     *
     */
    const DRAW = 'draw';

    /**
     * @var ResultRepository Doctrine repository object to access the 'result' table.
     */
    private $resultRepository;

    /**
     * StatsService constructor.
     * @param ResultRepository $resultRepository Doctrine repository object to access the 'result' table.
     */
    public function __construct(/*ResultRepository $resultRepository*/)
    {
//        $this->resultRepository = $resultRepository;
    }

    /**
     * Make the query to obtain the result type per player id.
     * This is a generic method that is called with a parameter to specify which one of the result types we should
     * obtain. The $type parameter is filtered and validated against a whitelist of possibilities to avoid weird SQL
     * injection shenanigans.
     *
     * @param int $id The ID of the player we want to check.
     * @param string $type The type of result to obtain from the database.
     *
     * @return int The amount of results that the user had that are of $type.
     * @throws Exception If the $type parameter is not on the whitelist ('win', 'lose', 'draw').
     */
    private function fetch(int $id, string $type) : int
    {
        $types = [self::WIN, self::LOSE, self::DRAW];

        if(!in_array($type, $types)) {
            throw new Exception('Cannot fetch statistics because you specified an invalid type!');
        }

        $type = trim(mb_strtolower($type));

        $query = $this->resultRepository->createQueryBuilder('w');
        $query->select($query->expr()->count('w.id'));

        $query->where('w.player = :playerId AND w.'.$type.' = true')->setParameter('playerId', $id, Type::INTEGER);
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Obtain the total amount of wins for a single player.
     * @param int $playerId The ID of the player we want to check.
     * @return int
     */
    private function countWinsFor(int $playerId) : int
    {
        return $this->fetch($playerId, self::WIN);
    }

    /**
     * Obtain the total amount of draws for a single player.
     * @param int $playerId The ID of the player we want to check.
     * @return int
     */
    private function countDrawsFor(int $playerId) : int
    {
        return $this->fetch($playerId, self::DRAW);
    }

    /**
     * Obtain the total amount of losses for a single player.
     * @param int $playerId The ID of the player we want to check.
     * @return int
     */
    private function countLosesFor(int $playerId) : int
    {
        return $this->fetch($playerId, self::LOSE);
    }

    /**
     * Obtains all the statistics (wins, losses and draws) for a player in a neatly packed array.
     * @param Player $player The player that we want to check.
     * @return array The compiled statistics of a single player.
     */
    public function all($player) : array
    {
        return [
            'win' => 0,
            'draw' => 0,
            'lose' => 0,
        ];
    }
}
