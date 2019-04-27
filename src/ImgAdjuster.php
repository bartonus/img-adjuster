<?php

/*
 * ImgAdjuster 2.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/bartonus/img-adjuster/
 */

namespace FixMind\ImgAdjuster;

use FixMind\ImgAdjuster\Config\Config;
use FixMind\ImgAdjuster\Config\Source;
use FixMind\ImgAdjuster\Config\Destination;
use FixMind\ImgAdjuster\Process\Process;

class ImgAdjuster
{
	private $config;
	private $source;

	public function __construct($source)
	{
		$this->setSource($source);
		$this->setConfig();
	}
	
	public function config($reset = false)
	{
		if($reset == true) $this->setConfig();
		return $this->config;
	}
	
	public function resize()
	{
		return $this->config->getResize();
	}
	
	public function watermark()
	{
		return $this->config->getWatermark();
	}
	
	public function saveAs($path, $mime = false)
	{
		return (new Process($this->source, new Destination($path, $mime), $this->config))->exe();
	}
	
	/* PRIVATE */
	
	private function setConfig()
	{
		$this->config = new Config();
	}
	
	private function setSource($source)
	{
		$this->source = new Source($source);
	}
	
}
