<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GameSet
 *
 * @ORM\Table(name="game_set", indexes={@ORM\Index(name="setguid_idx", columns={"guid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameSetRepository")
 */
class GameSet
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
     * @var string
     * @ORM\Column(name="guid")
     */
    private $guid;

    /**
     * @var bool
     * @ORM\Column(name="locked", type="boolean", nullable=true, options={"default": false})
     */
    private $locked;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="gameSet", cascade={"persist"})
     */
    private $games;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->games = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set guid
     *
     * @param string $guid
     *
     * @return GameSet
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     *
     * @return GameSet
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Add game
     *
     * @param \AppBundle\Entity\Game $game
     *
     * @return GameSet
     */
    public function addGame(\AppBundle\Entity\Game $game)
    {
        $this->games[] = $game;

        return $this;
    }

    /**
     * Remove game
     *
     * @param \AppBundle\Entity\Game $game
     */
    public function removeGame(\AppBundle\Entity\Game $game)
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
}
