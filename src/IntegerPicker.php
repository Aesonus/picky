<?php
/*
 * The software is Copyright (C)2019 by Digital Architects
 * The software includes all the files in the directory this file is located
 * This software makes use of open source software. Such software is governed
 * by their respective licenses
 * All Rights Reserved
 */
namespace Aesonus\Picky;

/**
 * Picks choices based on the integer chances that must total the upper limit
 * set in the constructor. This works by generating a random number between
 * 0 and $max and then comparing it to the chances set.
 *
 * @author Narya
 */
class IntegerPicker extends AbstractPicker
{
    /**
     * 
     * @var int
     */
    private $max;
    
    public function __construct(int $max = 99)
    {
        $this->max = $max;
    }
    
    /**
     * 
     * @return int
     */
    protected function rnd()
    {
        return mt_rand(0, $this->max);
    }
    
    /**
     * 
     * @param mixed $chances
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validateChances(...$chances): void
    {
        if (array_reduce($chances, function ($carry, $chance) {
            if (!is_int($chance)) {
                throw new \InvalidArgumentException('All chances must be integers'
                    . ' 0 - 100 at index $i');
            }
            return $carry + $chance;
        }, 0) !== 100) {
            throw new \InvalidArgumentException('Total chance must add up to 100');
        }
    }
}
