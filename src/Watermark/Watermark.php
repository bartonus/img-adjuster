<?php

/**
 * ImgAdjuster 1.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/fixmind/img-adjuster
 */

namespace fixmind\ImgAdjuster\Watermark;

use fixmind\ImgAdjuster\Config\Source;
use fixmind\ImgAdjuster\Resize\Position;

class Watermark
{
	private $source;
	private $alpha = 50;
	private $size = 50; // width in percent of destination image
	private $position;
	private $margin_value = 1;
	private $margin_type = 'percent';
	
	const defaultPosition = ['right', 'bottom'];
	
	public function __construct()
	{
		$this->position = new Position(self::defaultPosition[0], self::defaultPosition[1]);
	}

	/**
	 * --
	 * Set watermark image source
	 * --
	 * @param string
	 * @return this object
	 * --
	 * */
	public function setSrc($path)
	{
		$this->source = new Source($path, 'watermark');
		return $this;
	}

	/**
	 * --
	 * Set watermark image Alpha
	 * --
	 * @param integer 1..100
	 * @return this object
	 * --
	 * */
	public function setAlpha($alpha)
	{
		if (is_integer($alpha))
		{
			if ($alpha > 0 and $alpha <= 100)
			{
				$this->alpha = $alpha;
			}
			else throw new \Exception('Alpha value extends possible range. It should be between 1..100.');
		}
		else throw new \Exception('Alpha is not integer value.');
		return $this;
	}

	/**
	 * --
	 * Set watermark image Margin
	 * --
	 * @param integer, string (px,percent)
	 * @return this object
	 * --
	 * */
	public function setMargin($margin, $type = 'percent')
	{
		if (preg_match('/^(percent|px)$/', $type) == true)
		{
			$this->margin_type = $type;
		}
		else throw new \Exception('Wrong margin type "'.$type.'", it should be "percent" or "px".');
	
		if (is_integer($margin))
		{
			if ($margin >= 0 and $margin <= (($type == 'px') ? 1000 : 100))
			{
				$this->margin_value = $margin;
			}
			else throw new \Exception('Margin value extends possible range. Value should be between 1..' . (($type == 'px') ? 1000 : 100));
		}
		else throw new \Exception('Margin value is not integer.');
		return $this;
	}
	
	/**
	 * --
	 * Set watermark image Size depend on width of main image
	 * Percent of width main image
	 * --
	 * @param integer 1..100
	 * @return this object
	 * --
	 * */
	public function setSize($size)
	{
		if (is_integer($size))
		{
			if ($size >= 0 and $size <= 100)
			{
				$this->size = $size;
			}
			else throw new \Exception('Size value extends possible range.');
		}
		else throw new \Exception('Size is not integer value.');
		return $this;
	}

	/**
	 * --
	 * Set watermark position
	 * --
	 * @param string (left,right,center), string (top,center,bottom)
	 * @return this object
	 * --
	 * */
	public function setPosition($horizontal, $vertical)
	{
		$this->position->setPosition($horizontal, $vertical);
		return $this;
	}
	
	/* getter */
	
	public function getSource()
	{
		return $this->source;
	}
	
	public function getAlpha()
	{
		return $this->alpha;
	}
	
	public function getSize()
	{
		return $this->size;
	}
	
	public function getPosition()
	{
		return $this->position;
	}
	
	public function getMarginValue()
	{
		return $this->margin_value;
	}
	
	public function getMarginType()
	{
		return $this->margin_type;
	}

}
