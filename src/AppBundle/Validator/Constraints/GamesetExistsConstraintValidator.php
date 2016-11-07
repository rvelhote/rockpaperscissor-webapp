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

use AppBundle\Entity\GameSet;
use AppBundle\Repository\GameSetRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class GamesetExistsConstraint
 * @package AppBundle\Validator\Constraints
 */
class GamesetExistsConstraintValidator extends ConstraintValidator
{
    /**
     * @var GameSetRepository
     */
    private $repository;

    /**
     * GamesetExistsConstraintValidator constructor.
     * @param GameSetRepository $repository
     */
    public function __construct(GameSetRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Checks if the passed value is valid.
     * @param string $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var GameSet $gameset */
        $gameset = $this->repository->findGamesetByGuid($value);
        if(is_null($gameset)) {
            $this->context->buildViolation($constraint->message)->setParameter(':guid', $value)->addViolation();
        }
    }
}
