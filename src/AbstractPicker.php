<?php
/*
 * The software is Copyright (C)2019 by Digital Architects
 * The software includes all the files in the directory this file is located
 * This software makes use of open source software. Such software is governed
 * by their respective licenses
 * All Rights Reserved
 */
namespace Aesonus\Picky;

use LogicException;

/**
 * Defines a picker object
 *
 * @author Narya
 */
abstract class AbstractPicker implements PickerInterface
{
    /**
     * 
     * @var array
     */
    protected $chances = [];
    
    /**
     * 
     * @var array
     */
    protected $choices = [];
    
    public function getChances()
    {
        return $this->chances;
    }

    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * {@inheritdoc}
     */
    public function setChances(...$chances): PickerInterface
    {
        $this->validateChances(...$chances);
        $this->chances = $chances;
        return $this;
    }
    
    /**
     * 
     * @param int|float|string $chances Should be something that can convert to
     * a number.
     * @return void
     * @throws \InvalidArgumentException
     */
    abstract protected function validateChances(...$chances): void;
    
    /**
     * By default, performs no validation on choices
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validateChoices(...$choices): void {
        return ;
    }

    /**
     * {@inheritdoc}
     */
    public function setChoices(...$choices): PickerInterface
    {
        $this->choices = $choices;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function pick()
    {
        $this->assertSizeOfChancesAndChoicesAreTheSame();
        $combined = $this->combineChoicesAndChances();
        $rnd = $this->rnd();
        $carry = 0;
        foreach ($combined as $elements) {
            list($choice, $chance) = $elements;
            $carry += $chance;
            if ($rnd <= $carry) {
                return $choice;
            }
        }
    }
    
    protected function combineChoicesAndChances(): array
    {
        $combined = array_map(function ($choice, $chance) {
            return [$choice, $chance];
        }, $this->choices, $this->chances);
        
        return $combined;
    }
    
    /**
     * The random number generator
     * @return mixed Returns a random number or value
     */
    abstract protected function rnd();
    
    /**
     * 
     * @return void
     * @throws LogicException
     */
    protected function assertSizeOfChancesAndChoicesAreTheSame(): void
    {
        $exception = LogicException::class;
        if (empty($this->chances)) {
            throw new $exception('Chances must be set');
        } elseif (empty ($this->choices)) {
            throw new $exception('Choices must be set');
        }
        if (count($this->chances) !== count($this->choices)) {
            throw new $exception('Chances and choices must be same size');
        }
    }
}
