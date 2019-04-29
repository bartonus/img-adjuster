<?php

/*
 * ImgAdjuster 1.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/bartonus/img-adjuster/
 */

namespace fixmind\ImgAdjuster\Config;

class Position
{
	const LEFT = 'left';
	const RIGHT = 'right';
	const CENTER = 'center';
	
	const TOP = 'top';
	const MIDDLE = 'middle';
	const BOTTOM = 'bottom';
	
	const HORIZONTAL = [self::LEFT, self::CENTER, self::RIGHT];
	const VERTICAL = [self::TOP, self::MIDDLE, self::BOTTOM];
	const DEFAULT_POSITION = [self::RIGHT, self::BOTTOM];
	
	private $position;
	
	public function __construct($pHorizontal = false, $pVertical = false)
	{
		$this->setPosition((($pHorizontal == false) ? self::DEFAUL_POSITIONT[0] : $pHorizontal), (($pVertical == false) ? self::DEFAUL_POSITION[1] : $pVertical));
	}
	
	public function setPosition($pHorizontal, $pVertical)
	{
		if (in_array(strtolower($pHorizontal), self::HORIZONTAL) == true)
		{
			if (in_array(strtolower($pVertical), self::VERTICAL) == true)
			{
				$this->position = [strtolower($pHorizontal), strtolower($pVertical)];
			}
			else throw new \Exception('Vertical position value "'.$pVertical.'" is not in [' . implode(', ', self::VERTICAL) . ']');
		}
		else throw new \Exception('Horizontal position value "'.$pHorizontal.'" is not in [' . implode(', ', self::HORIZONTAL) . ']');
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
