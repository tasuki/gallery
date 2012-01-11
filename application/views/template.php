<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<link rel="icon" type="image/ico" href="<?php echo URL::base() ?>media/img/favicon.ico"/>

	<?php
	foreach ($styles as $style) {
		echo HTML::style($style, array('media' => 'screen'));
	}

	foreach ($scripts as $script) {
		echo HTML::script($script);
	}
	?>
</head>
<body>
	<?php echo $body ?>
</body>
</html>
