<?php
/*
 * The software is Copyright (C)2019 by Digital Architects
 * The software includes all the files in the directory this file is located
 * This software makes use of open source software. Such software is governed
 * by their respective licenses
 * All Rights Reserved
 */
namespace Aesonus\Picky;

use Iterator;
use LogicException;

/**
 *
 * @author Narya
 */
interface PickerInterface
{
    /**
     * Gets choices that the picker may pick
     * @return array|Iterator
     */
    public function getChoices();
    
    /**
     * Gets the chances that the picker will pick the respective choice
     * @return array|Iterator 
     */
    public function getChances();
    
    /**
     * Sets the choices that will be selected by random chance
     * @param mixed ...$choices
     * @return $this
     */
    public function setChoices(...$choices): PickerInterface;
    
    /**
     * 
     * @param mixed ...$chances
     * @return $this
     */
    public function setChances(...$chances): PickerInterface;
    
    /**
     * 
     * @return mixed Returns the randomly picked value
     * @throws LogicException Thrown if the number of choices don't add up to
     * chances, or if either are not set
     */
    public function pick();
}
