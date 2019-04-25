<?php

/**
 * ImgAdjuster 1.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/fixmind/img-adjuster/
 */

namespace fixmind\ImgAdjuster\Resize;

class Position
{
	private $position;
	
	const defaultPosition = ['right', 'bottom'];
	const horizontal = ['left', 'center', 'right'];
	const vertical = ['top', 'center', 'bottom'];
	
	public function __construct($horizontal = false, $vertical = false)
	{
		$this->setPosition((($horizontal == false) ? self::defaultPosition[0] : $horizontal), (($vertical == false) ? self::defaultPosition[1] : $vertical));
	}
	
	public function setPosition($horizontal, $vertical)
	{
		if (in_array(strtolower($horizontal), self::horizontal) == true)
		{
			if (in_array(strtolower($vertical), self::vertical) == true)
			{
				$this->position = [strtolower($horizontal), strtolower($vertical)];
			}
			else throw new \Exception('Vertical position value "'.$vertical.'" is not in [' . implode(', ', self::vertical) . ']');
		}
		else throw new \Exception('Horizontal position value "'.$horizontal.'" is not in [' . implode(', ', self::horizontal) . ']');
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
