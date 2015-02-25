<?php
use Imagine\Gd\Imagine,
	Imagine\Gd\Font,
	Imagine\Image\Point,
	Imagine\Image\Box;

require __DIR__.'/vendor/autoload.php';

$words = require __DIR__.'/words.php';
shuffle($words['adjectives']);
shuffle($words['nouns']);
$title = sprintf('JAD %s %s',
	ucwords(next($words['adjectives'])),
	ucwords(next($words['nouns']))
);

// Base logo
$imagine = new Imagine();
$base = $imagine->open('base.png');
$base = $base->resize($base->getSize()->scale(.5));
$baseHeight = $base->getSize()->getHeight();
$baseWidth = $base->getSize()->getWidth();

// Title color
$upperLeft = new Point(0,0);
$backgroundColor = $base->getColorAt($upperLeft);
$titleColor = $backgroundColor->lighten(100);

// Title font
$fonts = require __DIR__.'/fonts.php';
shuffle($fonts);
$titleFont = next($fonts);
$titleFont = new Font(__DIR__.'/fonts/'.$titleFont[0], $titleFont[1], $titleColor);
$titleBox = $titleFont->box($title);
echo $titleFont->getFile().PHP_EOL;

$titleMarginHorizontal = $baseWidth * .125;
$titleMarginVertical = ($baseHeight / 2) - ($titleBox->getHeight() / 2);
$titlePosition = new Point($baseWidth + $titleMarginHorizontal, $titleMarginVertical);

// Create banner and save
$bannerWidth = $baseWidth + ($titleMarginHorizontal * 2) + $titleBox->getWidth();
$banner = $imagine->create(new Box($bannerWidth, $baseHeight), $backgroundColor);
$banner->paste($base, $upperLeft);
$banner->draw()->text($title, $titleFont, $titlePosition);
$banner->save('banner.png');
