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
		'//fonts.googleapis.com/css?family=Bitter:400,700',
		'media/css/jquery.fancybox-1.3.1.css',
		'media/css/style.css',
	),

	'scripts' => array(
		'//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js',
		array(
			'test'     => 'window.jQuery',
			'fallback' => 'media/js/jquery-1.7.1.min.js',
		),
		'//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js',
		array(
			'test'     => 'window.jQuery.ui',
			'fallback' => 'media/js/jquery-ui-1.8.16.min.js',
		),
		'media/js/jquery.fancybox-1.3.1.pack.js',
		'media/js/jquery.easing-1.3.pack.js',
		'media/js/jquery.mousewheel-3.0.2.pack.js',
		'media/js/gallery.js',
	),
);
