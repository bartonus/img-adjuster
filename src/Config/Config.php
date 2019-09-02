<?php

/*
 * ImgAdjuster 2.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/fixmind/img-adjuster/
 */

namespace FixMind\ImgAdjuster\Config;

use FixMind\ImgAdjuster\Process\Resize;
use FixMind\ImgAdjuster\Watermark\Watermark;

class Config
{
	private $bw = false;
	private $quality = 80;
	private $resize;
	private $watermark;
	
	public function __construct()
	{
		$this->resize = new Resize();
		$this->watermark = new Watermark();
	}

	/**
	 * --
	 * Set resizer
	 * --
	 * @param Resize
	 * @return this object
	 * --
	 * */
	public function setResize(Resize $resize)
	{
		$this->resize = $resize;
		return $this;
	}

	/**
	 * --
	 * Set watermark
	 * --
	 * @param Watermark
	 * @return this object
	 * --
	 * */
	public function setWatermark(Watermark $watermark)
	{
		$this->watermark = $watermark;
		return $this;
	}
	
	/**
	 * --
	 * Set black&white mode
	 * --
	 * @param boolean
	 * @return this object
	 * --
	 * */
	public function setBW($bw)
	{
		$this->bw = (($bw == true) ? true : false);
		return $this;
	}
	
	/**
	 * --
	 * Set quality destination image
	 * --
	 * @param integer between 1..100
	 * @return this object
	 * --
	 * */
	public function setQuality($quality)
	{
		if(is_integer($quality))
		{
			if ($quality > 0 and $quality <= 100)
			{
				$this->quality = $quality;
			}
			else throw new \Exception('Quality value extends possible range.');
		}
		else throw new \Exception('Quality is not integer value.');
		return $this;
	}
	
	/* GETTER */
	
	public function getResize()
	{
		return $this->resize;
	}
	
	public function getWatermark()
	{
		return $this->watermark;
	}
	
	public function getQuality()
	{
		return $this->quality;
	}
	
	public function getCompressionLevelForPNG()
	{
		return 6;
	}
	
	public function getBW()
	{
		return $this->bw;
	}
}
