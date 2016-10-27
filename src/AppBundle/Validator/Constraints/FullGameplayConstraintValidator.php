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
use AppBundle\Entity\Player;
use AppBundle\Form\MakeMoveForm;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\GameSetRepository;
use AppBundle\Repository\MoveTypeRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
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
    private $gamesetRepository;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var MoveTypeRepository
     */
    private $moveTypeRepository;

    /**
     * @var Player
     */
    private $player;

    /**
     * FullGameplayConstraintValidator constructor.
     * @param GameSetRepository $gsr
     * @param GameRepository $gre
     * @param MoveTypeRepository $mtr
     * @param TokenStorage $token
     */
    public function __construct(GameSetRepository $gsr, GameRepository $gre, MoveTypeRepository $mtr, TokenStorage $token) {
        $this->gamesetRepository = $gsr;
        $this->gameRepository = $gre;
        $this->moveTypeRepository = $mtr;
        $this->player = $token->getToken()->getUser();
    }

    /**
     * This validator checks if the parameters that the client sent are valid. Various checks and cross-checks are
     * made between the POSTed form content and the gameset, game and move.
     *
     * Due to the fact that this validator involves all of the input it was not possible to make an individual
     * validator without having too much repeated code and many extra queries (although there are already many as is).
     *
     * @param MakeMoveForm $form The form that will be validated.
     * @param Constraint $constraint The validation constraint definition with all the messages.
     *
     * TODO Split this validator into various ClassConstraint validators?
     */
    public function validate($form, Constraint $constraint)
    {
        /** @var GameSet $gameset */
        $gameset = $this->gamesetRepository->findGamesetByGuid($form->getGameset());

        /** @var Game $game */
        $game = $this->gameRepository->findByGuid($form->getGame());

        /** @var MoveType $move */
        $move = $this->moveTypeRepository->findBySlug($form->getMove());

        if (is_null($gameset)) {
            $this->context
                ->buildViolation($constraint->gamesetDoesNotExist)
                ->setParameter(':guid', $form->getGameset())
                ->addViolation();
        }

        if(!is_null($gameset) && $gameset->getOwner()->getId() != $this->player->getId()) {
            $this->context
                ->buildViolation($constraint->wrongOwner)
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
                ->buildViolation($constraint->wrongGameset)
                ->setParameter(':gameGuid', $form->getGame())
                ->setParameter(':gamesetGuid', $form->getGameset())
                ->addViolation();
        }

        if(!is_null($game) && !is_null($game->getDatePlayed())) {
            $this->context
                ->buildViolation('This game was already played. You cannot replay it.')
                ->addViolation();
        }

        if(is_null($move)) {
            $this->context
                ->buildViolation($constraint->moveDoesNotExist)
                ->setParameter(':move', $form->getMove())
                ->addViolation();
        }

        if(!is_null($game) && !$game->getGameType()->getMoveTypes()->contains($move)) {
            $this->context
                ->buildViolation($constraint->wrongMove)
                ->setParameter(':move', $form->getMove())
                ->addViolation();
        }
    }
}
