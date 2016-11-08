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

use AppBundle\Entity\GameSet;
use AppBundle\Entity\MoveType;
use AppBundle\Entity\Player;
use AppBundle\Form\MakeMoveForm;
use AppBundle\Repository\GameSetRepository;
use AppBundle\Repository\MoveTypeRepository;
use AppBundle\Validator\Constraints\GamesetExists;
use AppBundle\Validator\Constraints\GamesetExistsValidator;
use AppBundle\Validator\Constraints\GamesetOwner;
use AppBundle\Validator\Constraints\GamesetOwnerValidator;
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
class GamesetOwnerValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $player;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $gameset;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $owner;

    /**
     * @var string
     */
    private $guid = '86f3e31d-daab-4b13-acf1-8180a40763ba';

    /**
     *
     */
    public function testDoesNotBelong()
    {
        $constraint = new GamesetOwner();
        $this->owner->expects($this->once())->method('getId')->willReturn(2);

        $this->validator->validate($this->guid, $constraint);
        $this->buildViolation($constraint->message)->setParameter(':guid', $this->guid)->assertRaised();
    }

    /**
     *
     */
    public function testBelongs()
    {
        $constraint = new GamesetOwner();
        $this->owner->expects($this->once())->method('getId')->willReturn(1);

        $this->validator->validate($this->guid, $constraint);
        $this->assertNoViolation();
    }

    /**
     * @return GamesetOwnerValidator
     */
    protected function createValidator() : GamesetOwnerValidator
    {
        $this->owner = $this->getMockBuilder(Player::class)->disableOriginalConstructor()->getMock();

        $this->gameset = $this->getMockBuilder(GameSet::class)->disableOriginalConstructor()->getMock();
        $this->gameset->expects($this->once())->method('getOwner')->willReturn($this->owner);

        $this->repository = $this->getMockBuilder(GameSetRepository::class)->disableOriginalConstructor()->getMock();
        $this->repository->expects($this->once())->method('findGamesetByGuid')->willReturn($this->gameset);

        $this->player = $this->getMockBuilder(Player::class)->disableOriginalConstructor()->getMock();
        $this->player->expects($this->once())->method('getId')->willReturn(1);

        return new GamesetOwnerValidator($this->repository, $this->player);
    }
}
