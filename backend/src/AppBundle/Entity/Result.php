<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Result
 *
 * @ORM\Table(name="result")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResultRepository")
 */
class Result
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
     * @var bool
     *
     * @ORM\Column(name="win", type="boolean", nullable=true, options={"default": false})
     */
    private $win;

    /**
     * @var bool
     *
     * @ORM\Column(name="lose", type="boolean", nullable=true, options={"default": false})
     */
    private $lose;

    /**
     * @var bool
     *
     * @ORM\Column(name="draw", type="boolean", nullable=true, options={"default": false})
     */
    private $draw;

    /**
     * @ORM\ManyToMany(targetEntity="Game", mappedBy="results")
     */
    private $games;

    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="results")
     */
    private $player;


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
     * Set win
     *
     * @param boolean $win
     *
     * @return Result
     */
    public function setWin($win)
    {
        $this->win = $win;

        return $this;
    }

    /**
     * Get win
     *
     * @return bool
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * Set lose
     *
     * @param boolean $lose
     *
     * @return Result
     */
    public function setLose($lose)
    {
        $this->lose = $lose;

        return $this;
    }

    /**
     * Get lose
     *
     * @return bool
     */
    public function getLose()
    {
        return $this->lose;
    }

    /**
     * Set draw
     *
     * @param boolean $draw
     *
     * @return Result
     */
    public function setDraw($draw)
    {
        $this->draw = $draw;

        return $this;
    }

    /**
     * Get draw
     *
     * @return bool
     */
    public function getDraw()
    {
        return $this->draw;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->games = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add game
     *
     * @param \AppBundle\Entity\Result $game
     *
     * @return Result
     */
    public function addGame(\AppBundle\Entity\Result $game)
    {
        $this->games[] = $game;

        return $this;
    }

    /**
     * Remove game
     *
     * @param \AppBundle\Entity\Result $game
     */
    public function removeGame(\AppBundle\Entity\Result $game)
    {
        $this->games->removeElement($game);
    }

    /**
     * Get games
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return Result
     */
    public function setPlayer(\AppBundle\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
