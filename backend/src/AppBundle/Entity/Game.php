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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Game
 *
 * @ORM\Table(name="game", indexes={@ORM\Index(name="guid_idx", columns={"guid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 * @ExclusionPolicy("all")
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
     * @var string
     * @ORM\Column(name="guid")
     * @Expose
     */
    private $guid;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="Player")
     * @Expose
     */
    private $player1;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="Player")
     * @Expose
     */
    private $player2;

    /**
     * @var DateTime
     * @ORM\Column(name="datePlayed", type="datetimetz", nullable=true)
     */
    private $datePlayed;

    /**
     * @var MoveType
     * @ORM\ManyToOne(targetEntity="MoveType")
     * @Expose
     * @Groups({"result"})
     */
    private $movePlayer1;

    /**
     * @var MoveType
     * @ORM\ManyToOne(targetEntity="MoveType")
     * @Expose
     * @Groups({"result"})
     */
    private $movePlayer2;

    /**
     * @ORM\ManyToOne(targetEntity="GameType")
     * @Expose
     */
    private $gameType;

    /**
     * @var int
     * @ORM\Column(name="result", type="smallint", nullable=true)
     * @Expose
     * @Groups({"result"})
     */
    private $result;

    /**
     * @ORM\ManyToOne(targetEntity="GameSet", inversedBy="games", cascade={"persist"})
     */
    private $gameSet;

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
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
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
     * Set result
     *
     * @param integer $result
     *
     * @return Game
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return integer
     */
    public function getResult()
    {
        return $this->result;
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

    /**
     * Set gameType
     *
     * @param \AppBundle\Entity\GameType $gameType
     *
     * @return Game
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

    /**
     * Set gameSet
     *
     * @param \AppBundle\Entity\GameSet $gameSet
     *
     * @return Game
     */
    public function setGameSet(\AppBundle\Entity\GameSet $gameSet = null)
    {
        $this->gameSet = $gameSet;

        return $this;
    }

    /**
     * Get gameSet
     *
     * @return \AppBundle\Entity\GameSet
     */
    public function getGameSet()
    {
        return $this->gameSet;
    }
}
