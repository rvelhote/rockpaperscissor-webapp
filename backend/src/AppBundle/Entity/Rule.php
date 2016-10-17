<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rule
 *
 * @ORM\Table(name="rule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RuleRepository")
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
