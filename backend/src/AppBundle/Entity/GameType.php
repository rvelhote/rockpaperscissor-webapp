<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GameType
 *
 * @ORM\Table(name="game_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameTypeRepository")
 */
class GameType
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
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="MoveType", inversedBy="gameTypes")
     *
     */
    private $moveTypes;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->moveTypes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return GameType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add moveType
     *
     * @param \AppBundle\Entity\MoveType $moveType
     *
     * @return GameType
     */
    public function addMoveType(\AppBundle\Entity\MoveType $moveType)
    {
        $this->moveTypes[] = $moveType;

        return $this;
    }

    /**
     * Remove moveType
     *
     * @param \AppBundle\Entity\MoveType $moveType
     */
    public function removeMoveType(\AppBundle\Entity\MoveType $moveType)
    {
        $this->moveTypes->removeElement($moveType);
    }

    /**
     * Get moveTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMoveTypes()
    {
        return $this->moveTypes;
    }

    /**
     * Add game
     *
     * @param \AppBundle\Entity\Game $game
     *
     * @return GameType
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
