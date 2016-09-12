<?php
/**
 * Created by PhpStorm.
 * User: rvelhote
 * Date: 9/12/16
 * Time: 10:13 PM
 */

namespace AppBundle\Validator\Constraints;


use AppBundle\Repository\GameRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class GameGuidExistsValidator extends ConstraintValidator
{
    /**
     * @var GameRepository
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
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        $game = $this->repository->findOneBy(['guid' => "xxx".$value]);

        $this->context->buildViolation($constraint->message)->setParameter('%string%', $value)->addViolation();

    }
}