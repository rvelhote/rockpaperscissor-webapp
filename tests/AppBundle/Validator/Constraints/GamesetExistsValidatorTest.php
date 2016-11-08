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
namespace Tests\AppBundle\Validator\Constraints;

use AppBundle\Entity\MoveType;
use AppBundle\Entity\Player;
use AppBundle\Form\MakeMoveForm;
use AppBundle\Repository\GameSetRepository;
use AppBundle\Repository\MoveTypeRepository;
use AppBundle\Validator\Constraints\GamesetExists;
use AppBundle\Validator\Constraints\GamesetExistsValidator;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use PHPUnit_Framework_MockObject_MockObject;
use AppBundle\Entity\Game;
use AppBundle\Repository\GameRepository;
use AppBundle\Validator\Constraints\FullGameplayConstraint;
use AppBundle\Validator\Constraints\FullGameplayConstraintValidator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

/**
 * Class FullGameplayConstraintValidatorTest
 * @package Tests\AppBundle\Validator\Constraints
 */
class GamesetExistsValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     *
     */
    public function testInvalidGamesetGuid()
    {
        $constraint = new GamesetExists();

        $guid = '86f3e31d-daab-4b13-acf1-8180a40763ba';
        $gameset = null;

        $this->repository->expects($this->once())->method('findGamesetByGuid')->willReturn($gameset);

        $this->validator->validate($guid, $constraint);
        $this->buildViolation($constraint->message)->setParameter(':guid', $guid)->assertRaised();
    }

    /**
     *
     */
    public function testValidGamesetGuid()
    {
        $constraint = new GamesetExists();

        $guid = '86f3e31d-daab-4b13-acf1-8180a40763ba';
        $gameset = $this->getMockBuilder(GameSetRepository::class)->disableOriginalConstructor()->getMock();

        $this->repository->expects($this->once())->method('findGamesetByGuid')->willReturn($gameset);

        $this->validator->validate($guid, $constraint);
        $this->assertNoViolation();
    }

    /**
     * @return GamesetExistsValidator
     */
    protected function createValidator()
    {
        $this->repository = $this->getMockBuilder(GameSetRepository::class)->disableOriginalConstructor()->getMock();
        return new GamesetExistsValidator($this->repository);
    }
}
