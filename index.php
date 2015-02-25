<?php
use Imagine\Gd\Imagine,
	Imagine\Gd\Font,
	Imagine\Image\Point,
	Imagine\Image\Box;

require __DIR__.'/vendor/autoload.php';

function fontBaseline(Font $font, $title) {
	if (!preg_match("/[gjpqy]/", $title)) {
		return 0;
	}

	$o = $font->box('o');
	$y = $font->box('y');
	return $y->getHeight() - $o->getHeight();
}

$words = require __DIR__.'/words.php';
shuffle($words['adjectives']);
shuffle($words['nouns']);
shuffle($words['slogans']);
$title = sprintf('JAD %s %s',
	ucwords(current($words['adjectives'])),
	ucwords(current($words['nouns']))
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
$titleColor = $backgroundColor->lighten(175);

// Title font
$fonts = require __DIR__.'/fonts.php';
shuffle($fonts);
$titleFontParams = current($fonts);
$titleFont = new Font(__DIR__.'/fonts/'.$titleFontParams[0], $titleFontParams[1], $titleColor);
$titleBaseline = fontBaseline($titleFont, $title);
$titleBox = $titleFont->box($title);

$titleMarginHorizontal = $baseWidth * .125;
$titleMarginVertical = ($baseHeight / 2) - (($titleBox->getHeight() - $titleBaseline) / 2);
$titlePosition = new Point($baseWidth + $titleMarginHorizontal, $titleMarginVertical);

// Slogan
$slogan = current($words['slogans']);
$sloganColor = $backgroundColor->lighten(100);
$sloganFont = new Font(__DIR__.'/fonts/'.$titleFontParams[0], (integer)($titleFontParams[1] * .5), $sloganColor);
$sloganBaseline = fontBaseline($sloganFont, $slogan);
$sloganBox = $sloganFont->box($slogan);

$sloganLeft = $titlePosition->getX() + (($titleBox->getWidth() - $sloganBox->getWidth()) / 2);
$sloganTop = $baseHeight - ($titleMarginVertical / 4) - $sloganBox->getHeight() + $sloganBaseline;
$sloganPosition = new Point($sloganLeft, $sloganTop);

// Create banner and save
$bannerWidth = $baseWidth + ($titleMarginHorizontal * 2) + $titleBox->getWidth();
$banner = $imagine->create(new Box($bannerWidth, $baseHeight), $backgroundColor);
$banner->paste($base, $upperLeft);
$banner->draw()->text($title, $titleFont, $titlePosition);
$banner->draw()->text($slogan, $sloganFont, $sloganPosition);
$banner->save('banner.png');
