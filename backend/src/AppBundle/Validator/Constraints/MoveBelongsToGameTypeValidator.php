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

use AppBundle\Entity\Game;
use AppBundle\Entity\MoveType;
use AppBundle\Form\MakeMoveForm;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\MoveTypeRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class MoveBelongsToGameTypeValidator
 * @package AppBundle\Validator\Constraints
 */
class MoveBelongsToGameTypeValidator extends ConstraintValidator
{
    /**
     * @var GameRepository Doctrine repository to access the database.
     */
    private $gameRepository;

    /**
     * @var MoveTypeRepository
     */
    private $moveTypeRepository;

    /**
     * GameGuidExistsValidator constructor.
     * @param GameRepository $gameRepository
     * @param MoveTypeRepository $moveTypeRepository
     * @internal param GameRepository $repository
     */
    public function __construct(GameRepository $gameRepository, MoveTypeRepository $moveTypeRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->moveTypeRepository = $moveTypeRepository;
    }

    /**
     * Checks if the passed value is valid.
     * Verify that the game GUID exists in the database and is playable.
     *
     * @param MakeMoveForm $value The value that should be validated.
     * @param Constraint $constraint The constraint for the validation.
     *
     * @throws \Exception
     *
     * TODO Replace by a query instead of looping and getting things. Much easier ;)
     */
    public function validate($value, Constraint $constraint)
    {
        $moveTypeExists = false;

        /** @var Game $game */
        $game = $this->gameRepository->findOneBy(['guid' => $value->game]);

        /** @var MoveType $move */
        $move = $this->moveTypeRepository->findOneBy(['slug' => $value->move]);

        if(!is_null($game)) {
            $moveTypes = $game->getGameType()->getMoveTypes();

            /** @var MoveType $moveType */
            foreach($moveTypes as $moveType) {
                if($moveType->getSlug() == $move->getSlug()) {
                    $moveTypeExists = true;
                    break;
                }
            }
        }

        // FIXME No violation is set if the game doesn't exist however it's caught by the GameGuidExists validator.
        if(!is_null($game) && !$moveTypeExists) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('%move%', mb_strtoupper($value->move))
                ->setParameter('%type%', $game->getGameType()->getName())
                ->addViolation();
        }
    }
}
