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

use AppBundle\Repository\MoveTypeRepository;
use AppBundle\Validator\Constraints\MoveExists;
use AppBundle\Validator\Constraints\MoveExistsValidator;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

/**
 * Class MoveExistsValidatorTest
 * @package Tests\AppBundle\Validator\Constraints
 */
class MoveExistsValidatorTest extends AbstractConstraintValidatorTest
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
        $constraint = new MoveExists();

        $slug = 'spock';
        $game = null;

        $this->repository->expects($this->once())->method('findBySlug')->willReturn($game);

        $this->validator->validate($slug, $constraint);
        $this->buildViolation($constraint->message)->setParameter(':move', $slug)->assertRaised();
    }

    /**
     *
     */
    public function testValidGamesetGuid()
    {
        $constraint = new MoveExists();

        $slug = 'rock';
        $move = $this->getMockBuilder(MoveTypeRepository::class)->disableOriginalConstructor()->getMock();

        $this->repository->expects($this->once())->method('findBySlug')->willReturn($move);

        $this->validator->validate($slug, $constraint);
        $this->assertNoViolation();
    }

    /**
     * @return MoveExistsValidator
     */
    protected function createValidator() : MoveExistsValidator
    {
        $this->repository = $this->getMockBuilder(MoveTypeRepository::class)->disableOriginalConstructor()->getMock();
        return new MoveExistsValidator($this->repository);
    }
}
