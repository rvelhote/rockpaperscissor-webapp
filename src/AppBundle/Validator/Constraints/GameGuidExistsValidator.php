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

use AppBundle\Repository\GameRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class GameGuidExistsValidator
 * @package AppBundle\Validator\Constraints
 */
class GameGuidExistsValidator extends ConstraintValidator
{
    /**
     * @var GameRepository Doctrine repository to access the database.
     */
    private $repository;

    /**
     * GameGuidExistsValidator constructor.
     * @param GameRepository $repository
     */
    public function __construct(GameRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Checks if the passed value is valid.
     * Verify that the game GUID exists in the database and is playable.
     *
     * @param mixed $value The value that should be validated.
     * @param Constraint $constraint The constraint for the validation.
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        $game = $this->repository->findOneBy(['guid' => $value]);
        if (is_null($game)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('%guid%', $value)
                ->addViolation();
        }
    }
}
