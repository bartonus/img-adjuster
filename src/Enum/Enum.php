<?php

/*
 * ImgAdjuster 2.0.1
 * Copyright 2018 Michal Barcikowski
 * Available via the MIT or new BSD @license.
 * Project: https://github.com/bartonus/img-adjuster/
 */

namespace FixMind\ImgAdjuster\Enum;

use \Exception;
use \ReflectionClass;

class Enum 
{
	private $value;
	
	public function __construct($value)
	{
		$this->value = $value;
	}
	
	public static function __callStatic($name, $arguments)
	{
		$class = get_called_class();
		$classConsts = (new ReflectionClass($class))->getConstants();
	
		if (in_array($name, $classConsts) == true)
		{
			return new $class($name);
		}
		else
		{
			throw new Exception("There is no '{$name}' value in '{$class}', Possible values: [" . implode(', ', $classConsts) . "]");
		}
	}
	
	public function __toString()
	{
		return (string) $this->value;
	}
}
