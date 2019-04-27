<?php

/*
 * ImgAdjuster 2.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/bartonus/img-adjuster/
 */

namespace FixMind\ImgAdjuster\Config;

class Destination
{
	private $path;
	private $mime;
	
	public function __construct($path, $mime = false)
	{
		$this->path = $path;
		
		// set destination image mime
		if ($mime == false)
		{
			$ext = explode('.', $path);
			$ext = strtolower($ext[count($ext) - 1]);
			
			switch($ext)
			{
				case 'gif':
					$this->mime = 'image/gif';
					break;
				case 'jpg':
				case 'jpeg':
				case 'jpe':
					$this->mime = 'image/jpeg';
					break;
				case 'png':
					$this->mime = 'image/png';
					break;
				default:
					throw new \Exception('Unrecognize destination file extension: "'.$ext.'".');
			}
		}
		else
		{
			$this->mime = $mime;
		}
		
		// sprawdzenie czy mime jest obslugiwany
		if (preg_match('/^image\/(gif|png|jpeg)$/', $this->mime) != true)
		{
			throw new \Exception('Not serviced mime destination file: "' . $this->mime . '".');
		}
	}
	
	public function getPath()
	{
		return $this->path;
	}
	
	public function getMime()
	{
		return $this->mime;
	}
}
