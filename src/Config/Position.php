<?php

/*
 * ImgAdjuster 2.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/bartonus/img-adjuster/
 */

namespace FixMind\ImgAdjuster\Config;

use FixMind\ImgAdjuster\Enum\Vertical;
use FixMind\ImgAdjuster\Enum\Horizontal;

class Position
{
	private $defaultPositionHorizontal = RIGHT;
	private $defaultPositionVertical = BOTTOM;
	
	private $position;
	
	public function __construct($pHorizontal = false, $pVertical = false)
	{
		$this->setPosition(
			(($pHorizontal == false) ? Horizontal::{$this->defaultPositionHorizontal}() : $pHorizontal),
			(($pVertical == false) ? Vertical::{$this->defaultPositionVertical}() : $pVertical)
		);
	}
	
	public function setPosition(Horizontal $pHorizontal, Vertical $pVertical)
	{
		$this->position = [$pHorizontal, $pVertical];
		return $this;
	}
	
	public function getPositionH()
	{
		return $this->position[0];
	}
	
	public function getPositionV()
	{
		return $this->position[1];
	}
}
