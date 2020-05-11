<?php

/*
 * This file is part of the aesonus/picky project.
 *
 * (c) Cory Laughlin <corylcomposinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aesonus\Tests;

use Aesonus\Picky\IntegerPicker;
use Aesonus\TestLib\BaseTestCase;
use InvalidArgumentException;

class IntegerPickerTest extends BaseTestCase
{
    public $testObj;

    protected function setUp() : void
    {
        //Tests the default 100
        $this->testObj = new IntegerPicker;
    }
    /**
     * @test
     */
    public function setChancesThrowsInvalidArgumentExceptionIfChancesDontAddUpTo100()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Total chance must add up to 100');
        $this->testObj->setChances(35, 35);
    }
    
    /**
     * @test
     */
    public function setChancesThrowsInvalidArgumentExceptionIfChancesAreNotIntegers()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All chances must be integers 0 - 100');
        $this->testObj->setChances(35, 35.6);
    }
    
    /**
     * @test
     */
    public function pickReturnsOneOfTheSetChoices()
    {
        $expected = ['choiceA', 'choiceB', 'choiceC'];
        $chances = [33, 33, 34];
        $actual = $this->testObj->setChoices(...$expected)->setChances(...$chances)
            ->pick();
        $this->assertContains($actual, $expected);
    }
}
