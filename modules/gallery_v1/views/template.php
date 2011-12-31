<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<link rel="icon" type="image/ico" href="<?php echo URL::base() ?>media/img/favicon.ico"/>

	<?php echo HTML::style('media/css/jquery.fancybox-1.3.1.css', array('media' => 'screen')) ?>
	<?php echo HTML::style('media/css/style.css', array('media' => 'screen')) ?>

	<?php echo HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js') ?>
	<?php echo HTML::script('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js') ?>
	<?php echo HTML::script('media/js/jquery.fancybox-1.3.1.pack.js') ?>
	<?php echo HTML::script('media/js/jquery.easing-1.3.pack.js') ?>
	<?php echo HTML::script('media/js/jquery.mousewheel-3.0.2.pack.js') ?>
	<?php echo HTML::script('media/js/gallery.js') ?>
</head>
<body>
	<?php echo $body ?>
</body>
</html>
