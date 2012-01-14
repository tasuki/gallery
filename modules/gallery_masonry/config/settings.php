<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'thumb' => array(
		// prefix for thumbnails, put something that will not
		// collide with your usual file names
		'prefix'  => '___',
		// method to create thumbnail (one of Resizer methods)
		'method'  => 'fit_into_grid',
		'size'    => 150,
		'gap'     => 12,
		'quality' => 85,
	),

	'scripts' => array(
		'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js',
		'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js',
		'media/js/jquery.fancybox-1.3.1.pack.js',
		'media/js/jquery.easing-1.3.pack.js',
		'media/js/jquery.mousewheel-3.0.2.pack.js',
		'media/js/jquery.imagesloaded.js',
		'media/js/jquery.masonry.min.js',
		'media/js/gallery.js',
	),
);
