<?php
use Imagine\Gd\Imagine,
	Imagine\Image\Point,
	Imagine\Image\Box;

require __DIR__.'/vendor/autoload.php';

$words = require __DIR__.'/words.php';
shuffle($words['adjectives']);
shuffle($words['nouns']);
$title = ucwords(sprintf('JAD %s %s, Interplanetary',
	next($words['adjectives']),
	next($words['nouns'])
));
echo $title.PHP_EOL;

$imagine = new Imagine();
$base = $imagine->open('base.png');
$base = $base->resize($base->getSize()->scale(.5));
$baseHeight = $base->getSize()->getHeight();
$baseWidth = $base->getSize()->getWidth();

$upperLeft = new Point(0,0);
$backgroundColor = $base->getColorAt($upperLeft);

$imagine->create(new Box($baseWidth * 5, $baseHeight), $backgroundColor)
	->paste($base, $upperLeft)
	->save('banner.png');
