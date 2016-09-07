<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Move
 *
 * @ORM\Table(name="move")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MoveRepository")
 */
class Move
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
     * @var int
     *
     * @ORM\Column(name="game", type="integer")
     */
    private $game;

    /**
     * @var int
     *
     * @ORM\Column(name="player", type="integer")
     */
    private $player;

    /**
     * @var int
     *
     * @ORM\Column(name="move", type="integer")
     */
    private $move;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetimetz")
     */
    private $dateCreated;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set game
     *
     * @param integer $game
     *
     * @return Move
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return int
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set player
     *
     * @param integer $player
     *
     * @return Move
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return int
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set move
     *
     * @param integer $move
     *
     * @return Move
     */
    public function setMove($move)
    {
        $this->move = $move;

        return $this;
    }

    /**
     * Get move
     *
     * @return int
     */
    public function getMove()
    {
        return $this->move;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Move
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
}

