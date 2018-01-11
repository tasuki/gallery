<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=0.8" />
	<title><?php echo $title; ?></title>
	<link rel="icon" type="image/ico" href="<?php echo URL::base() ?>media/img/favicon.ico"/>

	<?php
	foreach ($styles as $style) {
		echo HTML::style($style, array('media' => 'screen')) . "\n";
	}

	foreach ($scripts as $script) {
		if (is_array($script)) {
			// includes a fallback file if the test fails
			$alt = '%3Cscript type="text/javascript" src="'
				. URL::base() . $script['fallback'] . '"%3E%3C/script%3E';
			echo '<script type="text/javascript">' . $script['test']
				. " || document.write(unescape('$alt'));</script>\n";
		} else {
			// regular javascript file
			echo HTML::script($script) . "\n";
		}
	}

	@include APPPATH . 'config/extra_head.php';

	?>
</head>
<body>
	<?php echo $body ?>
</body>
</html>
