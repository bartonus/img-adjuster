<?php

/*
 * ImgAdjuster 2.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/bartonus/img-adjuster/
 */

namespace FixMind\ImgAdjuster\Config;

class Source
{
	private $source_path;
	private $source_width;
	private $source_height;
	private $source_mime;
	private $source_image_info;
	
	public function __construct($source, $comment = false)
	{
		if (file_exists($source))
		{
			// informacja o obrazku
			$this->source_image_info = getimagesize($source);
	
			// jesli JPEG to sprawdzenie orientacji
			if ($this->source_image_info['mime'] == 'image/jpeg')
			{
				$exif = exif_read_data($source);
				if (preg_match('/^(6|8)$/', $exif['Orientation']) == true)
				{
					$width = $this->source_image_info[0];
					$height = $this->source_image_info[1];
					$this->source_image_info[0] = $height;
					$this->source_image_info[1] = $width;
				}
			}
					
			// ustawienie parametrow
			if ($this->source_image_info != false)
			{
				if (preg_match('/^(image\/(gif|jpeg|png))$/', $this->source_image_info['mime']) == true)
				{
					$this->source_path = $source;
					$this->source_width = $this->source_image_info[0];
					$this->source_height = $this->source_image_info[1];
					$this->source_mime = $this->source_image_info['mime'];
				}
				else throw new \Exception('Source type file is unhandled: ' . $this->source_image_info['mime']);
			}
			else throw new \Exception('Source file is not an image.');
		}
		else throw new \Exception('Source '. $comment .' file doesn\'t exists.');
	}
	
	public function getPath()
	{
		return $this->source_path;
	}
	
	public function getWidth()
	{
		return $this->source_width;
	}
	
	public function getHeight()
	{
		return $this->source_height;
	}
	
	public function getMime()
	{
		return $this->source_mime;
	}
	
	public function getImageInfo()
	{
		return $this->source_image_info;
	}
}
