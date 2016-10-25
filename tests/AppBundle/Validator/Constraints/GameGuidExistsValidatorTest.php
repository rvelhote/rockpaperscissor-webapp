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

use PHPUnit_Framework_MockObject_MockObject;
use AppBundle\Entity\Game;
use AppBundle\Repository\GameRepository;
use AppBundle\Validator\Constraints\GameGuidExists;
use AppBundle\Validator\Constraints\GameGuidExistsValidator;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

/**
 * Class GameGuidExistsValidatorTest
 * @package Tests\AppBundle\Validator\Constraints
 */
class GameGuidExistsValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     *
     */
    public function testValidationOnInvalidGameGuid()
    {
        $message = 'Nie!';
        $guid = '86f3e31d-daab-4b13-acf1-8180a40763ba';

        $this->repository->method('findOneBy')->willReturn(null);

        $this->validator->validate($guid, new GameGuidExists(['message' => $message]));
        $this->buildViolation($message)->setParameter('%guid%', $guid)->assertRaised();
    }

    /**
     *
     */
    public function testValidationOnValidGameGuid()
    {
        $guid = '86f3e31d-daab-4b13-acf1-8180a40763ba';
        $game = $this->getMockBuilder(Game::class)->disableOriginalConstructor()->getMock();

        $this->repository->method('findOneBy')->willReturn($game);

        $this->validator->validate($guid, new GameGuidExists());
        $this->assertNoViolation();
    }

    /**
     * @return GameGuidExistsValidator
     */
    protected function createValidator()
    {
        $this->repository = $this->getMockBuilder(GameRepository::class)->disableOriginalConstructor()->getMock();
        return new GameGuidExistsValidator($this->repository);
    }
}
