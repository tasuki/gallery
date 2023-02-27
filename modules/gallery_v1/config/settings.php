<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'image' => array(
		// method to create image (one of Resizer methods)
		'method'  => 'fit_into_box',
		'size'    => 3072,
		'quality' => 85,
		'upscale' => false,
	),

	'thumb' => array(
		// prefix for thumbnails, put something that will not
		// collide with your usual file names
		'prefix'  => '__',
		// method to create thumbnail (one of Resizer methods)
		'method'  => 'fit_into_box',
		'size'    => 200,
		'quality' => 85,
		'upscale' => false,
	),

	'styles' => array(
		'https://fonts.googleapis.com/css?family=Bitter:400,700',
		'media/css/justifiedGallery.css',
		'media/css/baguetteBox-1.11.css',
		'media/css/style.css',
	),

	'scripts' => array(
		'media/js/jquery-3.3.1.min.js',
		'media/js/jquery.justifiedGallery.js',
		'media/js/baguetteBox-1.11.js',
		'media/js/gallery.js',
	),
);
