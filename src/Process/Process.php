<?php

/*
 * ImgAdjuster 2.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/bartonus/img-adjuster/
 */

namespace FixMind\ImgAdjuster\Process;

use FixMind\ImgAdjuster\Config\Config;
use FixMind\ImgAdjuster\Config\Source;
use FixMind\ImgAdjuster\Config\Destination;

class Process
{
	private $config;
	private $source;
	private $destination;
	
	/* resize transformations */
	private $destinationResize;
	
	private $destinationX;
	private $destinationY;
	private $destinationScaleX = 0;
	private $destinationScaleY = 0;
	private $destinationMoveX = 0;
	private $destinationMoveY = 0;
	
	private $srcMoveX = 0;
	private $srcMoveY = 0;
	private $srcCutX = 0;
	private $srcCutY = 0;
	
	public function __construct(Source $source, Destination $destination, Config $config)
	{
		$this->config = $config;
		$this->source = $source;
		$this->destination = $destination;
	}
	
	public function exe()
	{
		// przeliczenie wartosci
		$this->countResize();
		
		// zapisanie nowego pliku
		$this->process();
		
		// jesli plik zostal zapisany poprawnie
		return true;
	}
	
	/* PRIVATE */
	
	private function getImgFrom(Source $source)
	{
		switch ($source->getMime())
		{
			case 'image/gif':
				return imagecreatefromgif($source->getPath());
				
			case 'image/jpeg':
				
				$image = imagecreatefromjpeg($source->getPath());
				$exif = exif_read_data($source->getPath());				
				switch($exif['Orientation'])
				{
					case 3: $image = imagerotate($image, 180, 0); break;
					case 6: $image = imagerotate($image, -90, 0); break;
					case 8: $image = imagerotate($image, 90, 0); break;
				}				
				return $image;
				
			case 'image/png':
				return imagecreatefrompng($source->getPath());
				
			default:
				throw new \Exception('Unservice source file format: "' . $source->getMime() . '".');
				
		}
	}
	
	private function countResize($method = false)
	{
		// check if source is big enough
		// !!! trzeba dorobic sprawdzenie
		$this->destinationResize = true;
		
		// count size of image destination
		switch (($method == false) ? $this->config->getResize()->getMethod() : $method)
		{
			case 'to_X':
				$this->destinationX = $this->config->getResize()->getX();
				$this->destinationY = (int) (($this->config->getResize()->getX() / $this->source->getWidth()) * $this->source->getHeight());
				break;
			case 'to_Y':
				$this->destinationX = (int) (($this->config->getResize()->getY() / $this->source->getHeight()) * $this->source->getWidth());
				$this->destinationY = $this->config->getResize()->getY();
				break;
			case 'to_XY':
				$this->countResize('to_X');
				if ($this->destinationY > $this->config->getResize()->getY()) $this->countResize('to_Y');
				break;
			case 'to_Longer':
				
				if ($this->source->getWidth() > $this->source->getHeight())
				{
					$this->config->getResize()->setX($this->config->getResize()->getLength());
					$this->countResize('to_X');
				}
				else
				{
					$this->config->getResize()->setY($this->config->getResize()->getLength());
					$this->countResize('to_Y');
				}
				
				break;
				
			case 'to_Shorter':
				
				if ($this->source->getWidth() < $this->source->getHeight())
				{
					$this->config->getResize()->setX($this->config->getResize()->getLength());
					$this->countResize('to_X');
				}
				else
				{
					$this->config->getResize()->setY($this->config->getResize()->getLength());
					$this->countResize('to_Y');
				}
				
				break;
				
			case 'to_Fit':
				
				// crop image to exactly size
				if ($this->config->getResize()->getCrop() == true)
				{
					$this->countResizeFitCrop();
				}
				
				// resize image to max exactly size, dont crop, fill bg
				else
				{
					$this->countResizeFitDontCrop();
				}
				
				break;
				
			default :
				
				$this->destinationX = $this->source->getWidth();
				$this->destinationY = $this->source->getHeight();
				$this->destinationResize = false;
				
				break;
		}
	}
	
	private function countResizeFitCrop()
	{
		// resize image to X
		$this->countResize('to_X');
		
		// check if Y isn't shorter than should
		if ($this->destinationY < $this->config->getResize()->getY()) $this->countResize('to_Y');
		
		// crop by HEIGHT
		if ($this->destinationY > $this->config->getResize()->getY())
		{
			$cut = $this->destinationY - $this->config->getResize()->getY();
			
			// ustawienie przyciecia
			$this->srcCutX = 0;
			$this->srcCutY = ($cut * ($this->source->getHeight() / $this->destinationY));
			
			// ustawienie przesuniecia
			$this->srcMoveX = 0;
			switch($this->config->getResize()->getPosition()->getPositionV())
			{
				case TOP: $this->srcMoveY = 0; break;
				case MIDDLE: $this->srcMoveY = ($this->srcCutY / 2); break;
				case BOTTOM: $this->srcMoveY = $this->srcCutY; break;
			}
			
			// modyfikacja
			$this->destinationY = $this->config->getResize()->getY();
		}
		
		// crop by WIDTH
		else
		{
			$cut = $this->destinationX - $this->config->getResize()->getX();
			
			// ustawienie przyciecia
			$this->srcCutX = ($cut * ($this->source->getWidth() / $this->destinationX));
			$this->srcCutY = 0;
			
			// ustawienie przesuniecia
			switch($this->config->getResize()->getPosition()->getPositionH())
			{
				case LEFT: $this->srcMoveX = 0; break;
				case CENTER: $this->srcMoveX = ($this->srcCutX / 2); break;
				case RIGHT: $this->srcMoveX = $this->srcCutX; break;
			}
			$this->srcMoveY = 0;
			
			// modyfikacja
			$this->destinationX = $this->config->getResize()->getX();
		}
	}
	
	private function countResizeFitDontCrop()
	{
		$this->countResize('to_XY');
		
		// size of result image
		$this->destinationScaleX = $this->destinationX;
		$this->destinationScaleY = $this->destinationY;
		
		// size of source image fit to new size without crop
		$this->destinationX = $this->config->getResize()->getX();
		$this->destinationY = $this->config->getResize()->getY();
		
		// set image position Horizontal
		switch($this->config->getResize()->getPosition()->getPositionH())
		{
			case LEFT: $this->destinationMoveX = 0; break;
			case CENTER: $this->destinationMoveX = ($this->config->getResize()->getX() - $this->destinationScaleX) / 2; break;
			case RIGHT: $this->destinationMoveX = ($this->config->getResize()->getX() - $this->destinationScaleX); break;
		}
		
		// set image position Vertical
		switch($this->config->getResize()->getPosition()->getPositionV())
		{
			case TOP: $this->destinationMoveY = 0; break;
			case MIDDLE: $this->destinationMoveY = ($this->config->getResize()->getY() - $this->destinationScaleY) / 2; break;
			case BOTTOM: $this->destinationMoveY = ($this->config->getResize()->getY() - $this->destinationScaleY); break;
		}
		
	}
	
	private function addWatermark(&$destination)
	{
		if ($this->config->getWatermark()->getSource() != false)
		{
			// count size of watermark
			$watermarkWidth = round($this->destinationX * $this->config->getWatermark()->getSize() / 100);
			$watermarkHeight = round($watermarkWidth / $this->config->getWatermark()->getSource()->getWidth() * $this->config->getWatermark()->getSource()->getHeight());
			
			// count margin size
			if ($this->config->getWatermark()->getMarginType() == 'percent')
			{
				$margin = $this->config->getWatermark()->getMarginValue() * $this->destinationX / 100;
			}
			else 
			{
				$margin = $this->config->getWatermark()->getMarginValue();
			}
			
			// count horizontal position
			switch($this->config->getWatermark()->getPosition()->getPositionH())
			{
				case LEFT: $moveX = $margin; break;
				case CENTER: $moveX = ($this->destinationX - $watermarkWidth) / 2; break;
				case RIGHT: $moveX = ($this->destinationX - $watermarkWidth - $margin); break;
			}
			
			// count vertical position			
			switch($this->config->getWatermark()->getPosition()->getPositionV())
			{
				case TOP: $moveY = $margin; break;
				case MIDDLE: $moveY = ($this->destinationY - $watermarkHeight) / 2; break;
				case BOTTOM: $moveY = ($this->destinationY - $watermarkHeight - $margin); break;
			}
			
			// get img source
			$watermark = $this->getImgFrom($this->config->getWatermark()->getSource());
			
			// zachowanie przezroczystoci
			imagealphablending($watermark, false);
			$bgColor = imagecolorallocatealpha($watermark, 0, 0, 0, 127);
			imagefill($watermark, 0, 0, $bgColor);
			imagesavealpha($watermark, true);
			
			// przytlumienie
			imagefilter($watermark, IMG_FILTER_COLORIZE, 0, 0, 0, 127 * ((100 - $this->config->getWatermark()->getAlpha()) / 100));
			
			// insert watermark to image
			imagecopyresampled($destination, $watermark,
				
				/* move */
				$moveX, $moveY, 0, 0,
				
				/* size */
				$watermarkWidth,
				$watermarkHeight,
				$this->config->getWatermark()->getSource()->getWidth(),
				$this->config->getWatermark()->getSource()->getHeight());
		}
	}
	
	/* CORE */
	
	private function process()
	{
		// create destination image
		$destination = imagecreatetruecolor($this->destinationX, $this->destinationY);
		
		// fill background
		if ($this->config->getResize()->getBgColor() != false)
		{
			$bgColor = imagecolorallocate($destination, $this->config->getResize()->getBgColor(1), $this->config->getResize()->getBgColor(2), $this->config->getResize()->getBgColor(3));
			imagefill($destination, 0, 0, $bgColor);
		}
		
		// if PNG
		elseif($this->destination->getMime() == 'image/png')
		{
			imagealphablending($destination, true);
			$bgColor = imagecolorallocatealpha($destination, 0, 0, 0, 127);
			imagefill($destination, 0, 0, $bgColor);
			imagesavealpha($destination, true);
		}
		
		// put source image to destination image
		$source = $this->getImgFrom($this->source);

		// transform to grey scall soruce image
		if ($this->config->getBW() == true) imagefilter($source, IMG_FILTER_GRAYSCALE);
		
		// resampler
		if ($this->destinationResize == true)
		{
			imagecopyresampled($destination, $source,
				
				/* move */
				$this->destinationMoveX,
				$this->destinationMoveY,
				$this->srcMoveX,
				$this->srcMoveY,
				
				/* size */
				(($this->destinationScaleX == 0) ? $this->destinationX : $this->destinationScaleX),
				(($this->destinationScaleY == 0) ? $this->destinationY : $this->destinationScaleY),
				$this->source->getWidth() - $this->srcCutX,
				$this->source->getHeight() - $this->srcCutY);
		}
		else
		{
			imagecopy($destination, $source,
				
				/* move */
				0, 0, 0, 0,
				
				/* size */
				$this->destinationX, $this->destinationY);
		}
		
		// watermark
		$this->addWatermark($destination);
		
		// zapisanie obrazka
		switch ($this->destination->getMime())
		{
			case 'image/gif':
				imagegif($destination, $this->destination->getPath());
				break;
			case 'image/jpeg':
				imagejpeg($destination, $this->destination->getPath(), $this->config->getQuality());
				break;
			case 'image/png':
				imagepng($destination, $this->destination->getPath(), $this->config->getCompressionLevelForPNG());
				break;
			default:
				throw new \Exception('Can\'t create image, unserviced mime format: "' . $this->ext . '".');
		}
		
		// clear memory
		imagedestroy($destination);
		
		// process has finished succesfully
		return true;
	}
	
}