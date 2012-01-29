<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'image' => array(
		// method to create image (one of Resizer methods)
		'method'  => 'fit_into_box',
		'size'    => 1536,
		'quality' => 85,
	),

	'thumb' => array(
		// prefix for thumbnails, put something that will not
		// collide with your usual file names
		'prefix'  => '__',
		// method to create thumbnail (one of Resizer methods)
		'method'  => 'fit_into_box',
		'size'    => 200,
		'quality' => 85,
	),

	'styles' => array(
		'http://fonts.googleapis.com/css?family=Ubuntu:400,700&subset=latin,latin-ext',
		'media/css/jquery.fancybox-1.3.1.css',
		'media/css/style.css',
	),

	'scripts' => array(
		'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js',
		'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js',
		'media/js/jquery.fancybox-1.3.1.pack.js',
		'media/js/jquery.easing-1.3.pack.js',
		'media/js/jquery.mousewheel-3.0.2.pack.js',
		'media/js/gallery.js',
	),
);
