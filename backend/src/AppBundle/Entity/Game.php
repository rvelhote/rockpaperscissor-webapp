<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game", indexes={@ORM\Index(name="guid_idx", columns={"guid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
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
     * @var guid
     * @ORM\Column(name="guid", type="guid")
     */
    private $guid;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="Player")
     */
    private $player1;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="Player")
     */
    private $player2;

    /**
     * @var DateTime
     * @ORM\Column(name="dateCreated", type="datetimetz")
     */
    private $dateCreated;

    /**
     * @var DateTime
     * @ORM\Column(name="datePlayed", type="datetimetz", nullable=true)
     */
    private $datePlayed;

    /**
     * @var MoveType
     * @ORM\ManyToOne(targetEntity="MoveType")
     */
    private $movePlayer1;

    /**
     * @var MoveType
     * @ORM\ManyToOne(targetEntity="MoveType")
     */
    private $movePlayer2;


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
     * @param guid $guid
     *
     * @return Game
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return guid
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Game
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

    /**
     * Set datePlayed
     *
     * @param \DateTime $datePlayed
     *
     * @return Game
     */
    public function setDatePlayed($datePlayed)
    {
        $this->datePlayed = $datePlayed;

        return $this;
    }

    /**
     * Get datePlayed
     *
     * @return \DateTime
     */
    public function getDatePlayed()
    {
        return $this->datePlayed;
    }

    /**
     * Set player1
     *
     * @param \AppBundle\Entity\Player $player1
     *
     * @return Game
     */
    public function setPlayer1(\AppBundle\Entity\Player $player1 = null)
    {
        $this->player1 = $player1;

        return $this;
    }

    /**
     * Get player1
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * Set player2
     *
     * @param \AppBundle\Entity\Player $player2
     *
     * @return Game
     */
    public function setPlayer2(\AppBundle\Entity\Player $player2 = null)
    {
        $this->player2 = $player2;

        return $this;
    }

    /**
     * Get player2
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * Set movePlayer1
     *
     * @param \AppBundle\Entity\MoveType $movePlayer1
     *
     * @return Game
     */
    public function setMovePlayer1(\AppBundle\Entity\MoveType $movePlayer1 = null)
    {
        $this->movePlayer1 = $movePlayer1;

        return $this;
    }

    /**
     * Get movePlayer1
     *
     * @return \AppBundle\Entity\MoveType
     */
    public function getMovePlayer1()
    {
        return $this->movePlayer1;
    }

    /**
     * Set movePlayer2
     *
     * @param \AppBundle\Entity\MoveType $movePlayer2
     *
     * @return Game
     */
    public function setMovePlayer2(\AppBundle\Entity\MoveType $movePlayer2 = null)
    {
        $this->movePlayer2 = $movePlayer2;

        return $this;
    }

    /**
     * Get movePlayer2
     *
     * @return \AppBundle\Entity\MoveType
     */
    public function getMovePlayer2()
    {
        return $this->movePlayer2;
    }
}
