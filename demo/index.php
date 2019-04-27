<?php

include('../src/Enum/Enum.php');
include('../src/Enum/MarginType.php');
include('../src/Enum/Vertical.php');
include('../src/Enum/Horizontal.php');

include('../src/ImgAdjuster.php');
include('../src/process/Process.php');
include('../src/Config/Config.php');
include('../src/Config/Destination.php');
include('../src/Config/Source.php');
include('../src/Config/Position.php');
include('../src/Process/Resize.php');
include('../src/Watermark/Watermark.php');

use FixMind\ImgAdjuster\ImgAdjuster;
use FixMind\ImgAdjuster\Config\Position;
use FixMind\ImgAdjuster\Watermark\Watermark;
use FixMind\ImgAdjuster\Process\Resize;
use FixMind\ImgAdjuster\Enum\Vertical;
use FixMind\ImgAdjuster\Enum\Horizontal;
use FixMind\ImgAdjuster\Enum\MarginType;

try {

	$imgAdjuster = new ImgAdjuster('img/source.jpg');
	
	// ROW 1
	$imgAdjuster->config()
		->setBw(false)
		->setQuality(80)
		->setWatermark((new Watermark)->setSrc('img/watermark.png')->setPosition(Horizontal::CENTER(), Vertical::MIDDLE()))
		->setResize((new Resize)->toFit(250, 200)->setPosition(Horizontal::LEFT(), Vertical::MIDDLE()));
	$imgAdjuster->saveAs('img/photo_1.jpg');
	
	$imgAdjuster->resize()->setPosition(Horizontal::CENTER(), Vertical::MIDDLE());
	$imgAdjuster->saveAs('img/photo_2.jpg');
	
	$imgAdjuster->resize()->setPosition(Horizontal::RIGHT(), Vertical::MIDDLE());
	$imgAdjuster->saveAs('img/photo_3.jpg');

	// ROW 2
	$imgAdjuster->resize()->toFit(250, 300)->setPosition(Horizontal::LEFT(), Vertical::MIDDLE());
	$imgAdjuster->watermark()->setPosition(Horizontal::LEFT(), Vertical::TOP());
	$imgAdjuster->saveAs('img/photo_4.jpg');
	
	$imgAdjuster->config()->setBw(true);
	$imgAdjuster->resize()->toFit(250, 300)->setPosition(Horizontal::CENTER(), Vertical::MIDDLE());
	$imgAdjuster->watermark()->setPosition(Horizontal::CENTER(), Vertical::MIDDLE());
	$imgAdjuster->saveAs('img/photo_5.jpg');
	
	$imgAdjuster->config()->setBw(false);
	$imgAdjuster->resize()->toFit(250, 300)->setPosition(Horizontal::RIGHT(), Vertical::MIDDLE());
	$imgAdjuster->watermark()->setPosition(Horizontal::RIGHT(), Vertical::BOTTOM())->setMargin(10, MarginType::PX());
	$imgAdjuster->saveAs('img/photo_6.jpg');
	
	// ROW 3
	$imgAdjuster->resize()->toFit(250, 100)->setPosition(Horizontal::CENTER(), Vertical::TOP());
	$imgAdjuster->watermark()->setPosition(Horizontal::LEFT(), Vertical::BOTTOM())->setSize(20)->setMargin(10, MarginType::PX())->setAlpha(100);
	$imgAdjuster->saveAs('img/photo_7.jpg');
	
	$imgAdjuster->resize()->toFit(250, 100)->setPosition(Horizontal::CENTER(), Vertical::MIDDLE());
	$imgAdjuster->watermark()->setPosition(Horizontal::CENTER(), Vertical::BOTTOM());
	$imgAdjuster->saveAs('img/photo_8.jpg');
	
	$imgAdjuster->resize()->toFit(250, 100)->setPosition(Horizontal::CENTER(), Vertical::BOTTOM());
	$imgAdjuster->watermark()->setPosition(Horizontal::RIGHT(), Vertical::BOTTOM());
	$imgAdjuster->saveAs('img/photo_9.jpg');

}
catch(Exception $error)
{
	echo $error->getMessage();
	die();
}


?>
<!doctype html>
<html lang=pl>
    <head>
		<meta charset=utf-8>
		<title>imgAdjuster</title>
		<style>
			img { box-shadow: 5px 5px 5px #222; margin: 5px; width: 200px; } 
		</style>
	</head> 
	<body style="background:#777;">
		<img src="img/photo_1.jpg">
		<img src="img/photo_2.jpg">
		<img src="img/photo_3.jpg"><br>
		<img src="img/photo_4.jpg">
		<img src="img/photo_5.jpg">
		<img src="img/photo_6.jpg"><br>
		<img src="img/photo_7.jpg">
		<img src="img/photo_8.jpg">
		<img src="img/photo_9.jpg">
	</body>
</html>