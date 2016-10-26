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
use AppBundle\Entity\GameSet;
use AppBundle\Entity\MoveType;
use AppBundle\Form\MakeMoveForm;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\GameSetRepository;
use AppBundle\Repository\MoveTypeRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class FullGameplayConstraintValidator
 * @package AppBundle\Validator\Constraints
 */
class FullGameplayConstraintValidator extends ConstraintValidator
{
    /**
     * @var GameSetRepository
     */
    private $repository;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * FullGameplayConstraintValidator constructor.
     * @param GameSetRepository $repository
     */
    public function __construct(GameSetRepository $repository, GameRepository $gameRepository) {
        $this->repository = $repository;
        $this->gameRepository = $gameRepository;
    }

    /**
     * Checks if the passed value is valid.
     * Verify that the game GUID exists in the database and is playable.
     *
     * @param MakeMoveForm $form The value that should be validated.
     * @param Constraint $constraint The constraint for the validation.
     *
     * @throws \Exception
     *
     * TODO Replace by a query instead of looping and getting things. Much easier ;)
     */
    public function validate($form, Constraint $constraint)
    {
        /** @var GameSet $gameset */
        $gameset = $this->repository->findGamesetByGuid($form->getGameset());

        /** @var Game $game */
        $game = $this->gameRepository->findByGuid($form->getGame());

        if (is_null($gameset)) {
            $this->context
                ->buildViolation($constraint->gamesetDoesNotExist)
                ->setParameter(':guid', $form->getGameset())
                ->addViolation();
        }

        if(!is_null($gameset) && $gameset->getOwner()->getId() != 1) {
            $this->context
                ->buildViolation($constraint->gamesetWrongOwner)
                ->setParameter(':guid', $form->getGameset())
                ->addViolation();
        }

        if(is_null($game)) {
            $this->context
                ->buildViolation($constraint->gameDoesNotExist)
                ->setParameter(':guid', $form->getGame())
                ->addViolation();
        }

        if(!is_null($gameset) && !$gameset->getGames()->contains($game)) {
            $this->context
                ->buildViolation($constraint->gameDoesNotBelongToGameset)
                ->setParameter(':gameGuid', $form->getGame())
                ->setParameter(':gamesetGuid', $form->getGameset())
                ->addViolation();
        }
    }
}
