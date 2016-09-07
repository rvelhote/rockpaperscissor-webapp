<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
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
     *
     * @ORM\Column(name="uuid", type="guid", unique=true)
     */
    private $uuid;

    /**
     * @var int
     *
     * @ORM\Column(name="player1", type="integer")
     */
    private $player1;

    /**
     * @var int
     *
     * @ORM\Column(name="player2", type="integer")
     */
    private $player2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetimetz")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePlayed", type="datetimetz")
     */
    private $datePlayed;

    /**
     * @var int
     *
     * @ORM\Column(name="gameType", type="integer")
     */
    private $gameType;

    /**
     * @var int
     *
     * @ORM\Column(name="source", type="integer")
     */
    private $source;


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
     * Set uuid
     *
     * @param guid $uuid
     *
     * @return Game
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return guid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set player1
     *
     * @param integer $player1
     *
     * @return Game
     */
    public function setPlayer1($player1)
    {
        $this->player1 = $player1;

        return $this;
    }

    /**
     * Get player1
     *
     * @return int
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * Set player2
     *
     * @param integer $player2
     *
     * @return Game
     */
    public function setPlayer2($player2)
    {
        $this->player2 = $player2;

        return $this;
    }

    /**
     * Get player2
     *
     * @return int
     */
    public function getPlayer2()
    {
        return $this->player2;
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
     * Set gameType
     *
     * @param integer $gameType
     *
     * @return Game
     */
    public function setGameType($gameType)
    {
        $this->gameType = $gameType;

        return $this;
    }

    /**
     * Get gameType
     *
     * @return int
     */
    public function getGameType()
    {
        return $this->gameType;
    }

    /**
     * Set source
     *
     * @param integer $source
     *
     * @return Game
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return int
     */
    public function getSource()
    {
        return $this->source;
    }
}

