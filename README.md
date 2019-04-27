# ImgAdjuster
Simple image adjuster. You can, resize, crop, change main frame, add watermark with position and alpha settings

# Methods
- image config - setQuality, setBw
- image resize - toFit, toX, toY, toXY, toLonger, toShorter, setCrop, setPosition, setBgColor
- image watermark - setSrc, setPosition, setAlpha, setMargin

# Simply Usage
```php

// EXAMPLE 01
$imgAdjuster = new ImgAdjuster('source.jpeg');
$imgAdjuster->config()->setBw(false)->setQuality(90);
$imgAdjuster->saveAs('destination_01.jpg');

// EXAMPLE 02
$imgAdjuster->config()->setBw(true);
$imgAdjuster->resize()->toLonger(300)->setPosition(Horizontal::CENTER(), Vertical::MIDDLE());
$imgAdjuster->watermark()->setSrc('logo.png')->setPosition(Horizontal::CENTER(), Vertical::MIDDLE())->setAlpha(20)->setSize(20);
$imgAdjuster->saveAs('destination_02.jpg');

// EXAMPLE 03
$watermark = (new Watermark())->setSrc('logo.png')->setPosition(Horizontal::CENTER(), Vertical::TOP())->setAlpha(50);
$resize = (new Resize())->setFit(400, 300);
$imgAdjuster->config()->setWatermark($watermark)->setSize($resize);
$imgAdjuster->saveAs('destination_03.jpg'):


```
