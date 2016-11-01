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
use JMS\Serializer\Annotation\Expose;

/**
 * GameType
 *
 * @ORM\Table(name="game_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameTypeRepository")
 * @ExclusionPolicy("all")
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
     * @Expose
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="MoveType", inversedBy="gameTypes", cascade={"persist"})
     * @Expose
     *
     */
    private $moveTypes;

    /**
     * @ORM\OneToMany(targetEntity="Rule", mappedBy="gameType", cascade={"persist"})
     *
     */
    private $rules;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->moveTypes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rules = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add rule
     *
     * @param \AppBundle\Entity\Rule $rule
     *
     * @return GameType
     */
    public function addRule(\AppBundle\Entity\Rule $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Remove rule
     *
     * @param \AppBundle\Entity\Rule $rule
     */
    public function removeRule(\AppBundle\Entity\Rule $rule)
    {
        $this->rules->removeElement($rule);
    }

    /**
     * Get rules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRules()
    {
        return $this->rules;
    }
}
