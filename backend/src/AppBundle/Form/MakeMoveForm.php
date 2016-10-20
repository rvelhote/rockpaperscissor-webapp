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
namespace AppBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MakeMoveForm
 * @package AppBundle\Form
 * @AppBundle\Validator\Constraints\MoveBelongsToGameType()
 */
class MakeMoveForm
{
    /**
     * @Assert\NotBlank()
     * @var string
     */
    protected $gameset = '';

    /**
     * @Assert\NotBlank()
     * AppBundle\Validator\Constraints\GameGuidExists()
     * @var string
     */
    protected $game = '';

    /**
     * @Assert\NotBlank()
     * AppBundle\Validator\Constraints\MoveExists()
     * @var string
     */
    protected $move = '';

    /**
     * @return string
     */
    public function getGameset()
    {
        return $this->gameset;
    }

    /**
     * @param $gameset
     */
    public function setGameset($gameset)
    {
        $this->gameset = $gameset;
    }

    /**
     * @return string
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param string $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return string
     */
    public function getMove()
    {
        return $this->move;
    }

    /**
     * @param string $move
     */
    public function setMove($move)
    {
        $this->move = $move;
    }
}
