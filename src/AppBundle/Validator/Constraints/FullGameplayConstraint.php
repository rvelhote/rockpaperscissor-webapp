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
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class MoveBelongsToGameType
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class FullGameplayConstraint extends Constraint
{
    /**
     * @var string
     */
    public $gamesetDoesNotExist = 'The gameset (:guid) does not exist.';

    /**
     * @var string
     */
    public $wrongOwner = 'You do not own the gameset (:guid) you are trying to play.';

    /**
     * @var string
     */
    public $gameDoesNotExist = 'This game (:guid) does not exist.';

    /**
     * @var string
     */
    public $wrongGameset = 'The game you are trying to play (:gameGuid) does not belong to the gameset (:gamesetGuid)';

    /**
     * @var string
     */
    public $moveDoesNotExist = 'The move ":move" does not exist.';

    /**
     * @var string
     */
    public $wrongMove = 'The move ":move" does not belong in the game type specified by this game.';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
