<?php
/*
 * The software is Copyright (C)2019 by Digital Architects
 * The software includes all the files in the directory this file is located
 * This software makes use of open source software. Such software is governed
 * by their respective licenses
 * All Rights Reserved
 */
namespace Aesonus\Tests;

use Aesonus\Picky\AbstractPicker;
use Aesonus\TestLib\BaseTestCase;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;

/**
 *
 * @author Narya
 */
class AbstractPickerTest extends BaseTestCase
{
    /**
     * 
     * @var AbstractPicker|MockObject
     */
    private $testObj;
    
    protected function setUp(): void
    {
        $this->testObj = $this->getMockBuilder(AbstractPicker::class)
            ->getMockForAbstractClass();
    }
    
    /**
     * @test
     */
    public function getChancesReturnsSetChances()
    {
        $expected = [75, 25];
        $actual = $this->testObj->setChances(...$expected)->getChances();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @dataProvider pickThrowsLogicExceptionIfChancesAndChoicesDoNotHaveSameSizeDataProvider
     */
    public function pickThrowsLogicExceptionIfChancesAndChoicesDoNotHaveSameSize($chances, $choices)
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Chances and choices must be same size');
        
        $this->testObj->setChances(...$chances)->setChoices(...$choices)->pick();
    }
    
    /**
     * Data Provider
     */
    public function pickThrowsLogicExceptionIfChancesAndChoicesDoNotHaveSameSizeDataProvider()
    {
        return [
            'Chances > Choices' => [[50,50], ['choiceA']],
            'Choices > Chances' => [[100], new \ArrayObject(['choiceA', 'choiceB'])],
        ];
    }
    
    /**
     * @test
     * @dataProvider pickThrowsLogicExceptionIfChancesOrChoicesNotSetDataProvider
     */
    public function pickThrowsLogicExceptionIfChancesOrChoicesNotSet($chances, $choices, $expectedMessage)
    {
        if ($chances) {
            $this->testObj->setChances(...$chances);
        }
        if ($choices) {
            $this->testObj->setChoices(...$choices);
        }
        
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage($expectedMessage);
        $this->testObj->pick();
    }

    /**
     * Data Provider
     */
    public function pickThrowsLogicExceptionIfChancesOrChoicesNotSetDataProvider()
    {
        return [
            [null, ['choiceA'], 'Chances must be set'],
            [[100], null, 'Choices must be set'],
            [null, null, 'must be set']
        ];
    }
    
    /**
     * @test
     * @dataProvider pickReturnsOneOfTheSetChoicesBasedOnRndMethodDataProvider
     */
    public function pickReturnsOneOfTheSetChoices($rnd, $chances, $expected)
    {
        //Set up what the rnd will return
        $this->testObj->expects($this->once())->method('rnd')
            ->willReturn($rnd);
        $actual = $this->testObj
            ->setChoices('choiceA', 'choiceB', 'choiceC')
            ->setChances(...$chances)
            ->pick();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Data Provider
     */
    public function pickReturnsOneOfTheSetChoicesBasedOnRndMethodDataProvider()
    {
        //We assume that three choices will be given in the test
        return [
            'lower limit a' => [0, [33, 33, 34], 'choiceA'],
            'lower limit b' => [34, [33, 33, 34], 'choiceB'],
            'lower limit c' => [67, [33, 33, 34], 'choiceC'],
            'upper limit a' => [33, [33, 33, 34], 'choiceA'],
            'upper limit b' => [66, [33, 33, 34], 'choiceB'],
            'upper limit c' => [99, [33, 33, 34], 'choiceC'],
        ];
    }
}
