<?php

/*
 * ImgAdjuster 1.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/bartonus/img-adjuster/
 */

namespace bartonus\ImgAdjuster\Resize;

use bartonus\ImgAdjuster\Config\Position;

class Resize
{
	private $method;
	private $x;
	private $y;
	private $length;
	private $crop = true;
	private $bgcolor;
	private $position;
	
	/*
	 * --
	 * Set image resolution to fixed X length
	 * Y length will be calculated proportionally
	 * --
	 * image resolution has the same proportions as source
	 * --
	 * @param integer between 1..10000
	 * @return this object
	 * --
	 * */
	public function toX($x)
	{
		$this->setMethod('to_X');
		$this->setX($x);
		return $this;
	}

	/*
	 * --
	 * Set image resolution to fixed Y length
	 * X length will be calculated proportionally
	 * --
	 * image resolution has the same proportions as source
	 * --
	 * @param integer between 1..10000
	 * @return this object
	 * --
	 * */
	public function toY($y)
	{
		$this->setMethod('to_Y');
		$this->setY($y);
		return $this;
	}
	
	/*
	 * --
	 * Set image resolution to fixed X or Y length
	 * both length will not be exceeded
	 * --
	 * image resolution has the same proportions as source
	 * --
	 * @param integer between 1..10000
	 * @return this object
	 * --
	 * */
	public function toXY($x, $y)
	{
		$this->setMethod('to_XY');
		$this->setX($x);
		$this->setY($y);
		return $this;
	}

	/*
	 * --
	 * Set image resolution to fixed Longer length
	 * second length will be calculated proportionally
	 * --
	 * image resolution has the same proportions as source
	 * --
	 * @param integer between 1..10000
	 * @return this object
	 * --
	 * */
	public function toLonger($length)
	{
		$this->setMethod('to_Longer');
		$this->setLength($length);
		return $this;
	}

	/*
	 * --
	 * Set image resolution to fixed shorter length
	 * second length will be calculated proportionally
	 * --
	 * image resolution has the same proportions as source
	 * --
	 * @param integer between 1..10000
	 * @return this object
	 * --
	 * */
	public function toShorter($length)
	{
		$this->setMethod('to_Shorter');
		$this->setLength($length);
		return $this;
	}
	
	/*
	 * --
	 * Set image strict resolution X & Y
	 * crop=true => surplus will be cropped
	 * crop=false => the deficiency will be filled with a solid color
	 * --
	 * @param integer between 1..10000
	 * @return this object
	 * --
	 * */
	public function toFit($x, $y, $crop = true, $bgcolor = false, $positionH = Position::CENTER, $positionV = Position::MIDDLE)
	{
		$this->setMethod('to_Fit');
		$this->setX($x);
		$this->setY($y);
		$this->setCrop($crop);
		$this->setBgColor($bgcolor);
		$this->setPosition($positionH, $positionV);
		return $this;
	}

	/*
	 * --
	 * Set image main frame it is helpful if you crop toFit()
	 * --
	 * @param string (left,center,right), string (top,middle,bottom)
	 * @return this object
	 * --
	 * */
	public function setPosition($horizontal, $vertical)
	{
		$this->position = new Position($horizontal, $vertical);
		return $this;
	}
	
	/*
	 * --
	 * Set crop on/off
	 * --
	 * @param boolean
	 * @return this object
	 * --
	 * */
	public function setCrop($crop = true)
	{
		$this->crop = (($crop == true) ? true : false);
		return $this;
	}

	/*
	 * --
	 * Set background when crop is off
	 * --
	 * @param boolean
	 * @return this object
	 * --
	 * */
	public function setBgColor($bgcolor)
	{
		$this->bgcolor = ((is_array($bgcolor) && count($bgcolor) == 3) ? $bgcolor : false);
		return $this;
	}
	
	/* PRIVATE SETTER */
	
	private function setX($x)
	{
		if (is_integer($x))
		{
			if ($x >= 0 and $x <= 10000)
			{
				$this->x = $x;
			}
			else throw new \Exception('X value is out of range.');
		}
		else throw new \Exception('X value is not integer.');
		return $this;
	}
	
	private function setY($y)
	{
		if (is_integer($y))
		{
			if ($y >= 0 and $y <= 10000)
			{
				$this->y = $y;
			}
			else throw new \Exception('Y value is out of range.');
		}
		else throw new \Exception('Y value is not integer.');
		return $this;
	}
	
	private function setLength($length)
	{
		if (is_integer($length))
		{
			if ($length >= 0 and $length <= 10000)
			{
				$this->length = $length;
			}
			else throw new \Exception('Length value is out of range.');
		}
		else throw new \Exception('Length value is not integer.');
		return $this;
	}

	private function setMethod($method)
	{
		if (preg_match('/^(to_X|to_Y|to_XY|to_Shorter|to_Longer|to_Fit)$/', $method) == true)
		{
			$this->method = $method;
		}
		else throw new \Exception("Resize method out of range: {$method}");
		return $this;
	}
	
	/* GETTER */
	
	/*
	 * --
	 * Get Resize Method
	 * --
	 * @return string
	 * --
	 * */
	public function getMethod()
	{
		return $this->method;
	}
	
	public function getX()
	{
		return $this->x;
	}
	
	public function getY()
	{
		return $this->y;
	}
	
	public function getLength()
	{
		return $this->length;
	}
	
	public function getCrop()
	{
		return $this->crop;
	}
	
	public function getBgColor($color = 0)
	{
		return (($color == 0) ? $this->bgcolor : $this->bgcolor[($color - 1)]);
	}
	
	public function getPosition()
	{
		return $this->position;
	}
}

