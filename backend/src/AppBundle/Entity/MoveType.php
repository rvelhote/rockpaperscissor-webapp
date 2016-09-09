<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MoveType
 *
 * @ORM\Table(name="move_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MoveTypeRepository")
 */
class MoveType
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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="GameType", mappedBy="moveTypes")
     * @ORM\JoinTable(name="games_moves")
     */
    private $gameTypes;
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gameTypes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return MoveType
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
     * Set slug
     *
     * @param string $slug
     *
     * @return MoveType
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add gameType
     *
     * @param \AppBundle\Entity\GameType $gameType
     *
     * @return MoveType
     */
    public function addGameType(\AppBundle\Entity\GameType $gameType)
    {
        $this->gameTypes[] = $gameType;

        return $this;
    }

    /**
     * Remove gameType
     *
     * @param \AppBundle\Entity\GameType $gameType
     */
    public function removeGameType(\AppBundle\Entity\GameType $gameType)
    {
        $this->gameTypes->removeElement($gameType);
    }

    /**
     * Get gameTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGameTypes()
    {
        return $this->gameTypes;
    }
}
