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
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Rule
 *
 * @ORM\Table(name="rule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RuleRepository")
 * @ExclusionPolicy("all")
 */
class Rule
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="MoveType")
     */
    private $winner;

    /**
     * @ORM\ManyToOne(targetEntity="MoveType")
     */
    private $loser;

    /**
     * @var string
     * @ORM\Column(name="outcome", type="string", length=255)
     */
    private $outcome;

    /**
     * @ORM\ManyToOne(targetEntity="GameType", inversedBy="rules")
     */
    private $gameType;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set outcome
     *
     * @param string $outcome
     *
     * @return Rule
     */
    public function setOutcome($outcome)
    {
        $this->outcome = $outcome;

        return $this;
    }

    /**
     * Get outcome
     *
     * @return string
     */
    public function getOutcome()
    {
        return $this->outcome;
    }

    /**
     * Set winner
     *
     * @param \AppBundle\Entity\MoveType $winner
     *
     * @return Rule
     */
    public function setWinner(\AppBundle\Entity\MoveType $winner = null)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get winner
     *
     * @return \AppBundle\Entity\MoveType
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set loser
     *
     * @param \AppBundle\Entity\MoveType $loser
     *
     * @return Rule
     */
    public function setLoser(\AppBundle\Entity\MoveType $loser = null)
    {
        $this->loser = $loser;

        return $this;
    }

    /**
     * Get loser
     *
     * @return \AppBundle\Entity\MoveType
     */
    public function getLoser()
    {
        return $this->loser;
    }

    /**
     * Set gameType
     *
     * @param \AppBundle\Entity\GameType $gameType
     *
     * @return Rule
     */
    public function setGameType(\AppBundle\Entity\GameType $gameType = null)
    {
        $this->gameType = $gameType;

        return $this;
    }

    /**
     * Get gameType
     *
     * @return \AppBundle\Entity\GameType
     */
    public function getGameType()
    {
        return $this->gameType;
    }
}
