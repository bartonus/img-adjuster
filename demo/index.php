<?php

include('../src/ImgAdjuster.php');
include('../src/Config/Config.php');
include('../src/Config/Destination.php');
include('../src/Config/Source.php');
include('../src/Resize/Resize.php');
include('../src/Resize/Position.php');
include('../src/Resize/Process.php');
include('../src/Watermark/Watermark.php');

use fixmind\ImgAdjuster\ImgAdjuster;
use fixmind\ImgAdjuster\Watermark\Watermark;
use fixmind\ImgAdjuster\Resize\Resize;

try {

	$imgAdjuster = new ImgAdjuster('img/source.jpg');
	
	// ROW 1
	$imgAdjuster->config()
		->setBw(false)
		->setQuality(80)
		->setWatermark((new Watermark)->setSrc('img/watermark.png')->setPosition('center', 'center'))
		->setResize((new Resize)->toFit(250, 200)->setPosition('left', 'center'));
	$imgAdjuster->saveAs('img/photo_1.jpg');
	
	$imgAdjuster->resize()->setPosition('center', 'center');
	$imgAdjuster->saveAs('img/photo_2.jpg');
	
	$imgAdjuster->resize()->setPosition('right', 'center');
	$imgAdjuster->saveAs('img/photo_3.jpg');
	
	// ROW 2
	$imgAdjuster->resize()->toFit(250, 300)->setPosition('left', 'center');
	$imgAdjuster->watermark()->setPosition('center', 'top');
	$imgAdjuster->saveAs('img/photo_4.jpg');
	
	$imgAdjuster->config()->setBw(true);
	$imgAdjuster->resize()->toFit(250, 300)->setPosition('center', 'center');
	$imgAdjuster->watermark()->setPosition('center', 'center');
	$imgAdjuster->saveAs('img/photo_5.jpg');

	$imgAdjuster->config()->setBw(false);
	$imgAdjuster->resize()->toFit(250, 300)->setPosition('right', 'center');
	$imgAdjuster->watermark()->setPosition('center', 'bottom')->setMargin(25, 'px');
	$imgAdjuster->saveAs('img/photo_6.jpg');
	
	// ROW 3
	$imgAdjuster->resize()->toFit(250, 100)->setPosition('center', 'top');
	$imgAdjuster->watermark()->setPosition('left', 'bottom')->setSize(20)->setMargin(10, 'px')->setAlpha(100);
	$imgAdjuster->saveAs('img/photo_7.jpg');
	
	$imgAdjuster->resize()->toFit(250, 100)->setPosition('center', 'center');
	$imgAdjuster->watermark()->setPosition('center', 'bottom');
	$imgAdjuster->saveAs('img/photo_8.jpg');
	
	$imgAdjuster->resize()->toFit(250, 100)->setPosition('center', 'bottom');
	$imgAdjuster->watermark()->setPosition('right', 'bottom');
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
	</head> 
	<body style="background:#777;">
		<img src="img/photo_1.jpg" style="box-shadow: 5px 5px 5px #222; margin: 5px;">
		<img src="img/photo_2.jpg" style="box-shadow: 5px 5px 5px #222; margin: 5px;">
		<img src="img/photo_3.jpg" style="box-shadow: 5px 5px 5px #222; margin: 5px;"><br>
		<img src="img/photo_4.jpg" style="box-shadow: 5px 5px 5px #222; margin: 5px;">
		<img src="img/photo_5.jpg" style="box-shadow: 5px 5px 5px #222; margin: 5px;">
		<img src="img/photo_6.jpg" style="box-shadow: 5px 5px 5px #222; margin: 5px;"><br>
		<img src="img/photo_7.jpg" style="box-shadow: 5px 5px 5px #222; margin: 5px;">
		<img src="img/photo_8.jpg" style="box-shadow: 5px 5px 5px #222; margin: 5px;">
		<img src="img/photo_9.jpg" style="box-shadow: 5px 5px 5px #222; margin: 5px;">
	</body>
</html>