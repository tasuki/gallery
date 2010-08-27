<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo strip_tags($breadcrumb); ?></title>
	<link rel="icon" type="image/png" href="<?php echo $conf['basedir']; ?>favicon.png"/>

	<script type="text/javascript" src="<?php echo $conf['basedir']; ?>fancybox/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $conf['basedir']; ?>fancybox/jquery.fancybox-1.3.1.pack.js"></script>
	<script type="text/javascript" src="<?php echo $conf['basedir']; ?>fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="<?php echo $conf['basedir']; ?>fancybox/jquery.mousewheel-3.0.2.pack.js"></script>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $conf['basedir']; ?>fancybox/jquery.fancybox-1.3.1.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $conf['basedir']; ?>style.css"/>

	<script>
		$(document).ready(function(){
			$("#images a").fancybox({
				'margin'         : 0,
				'padding'        : 0,
				'changeSpeed'    : 0,
				'overlayShow'    : true,
				'overlayOpacity' : 0.95,
				'titlePosition'  : 'over',
			});
		});
	</script>
</head>
<body>

<div id="breadcrumb">
<?php echo $breadcrumb; ?>
</div>

<div id="directories">
<?php
	foreach ($directories as $dir) {
		echo '<a class="box" href="' . $conf['basedir'] . $directory . $dir . '">' . displayify($dir) . '</a>';
	}
?>
</div>

<div class="clear"></div>

<div id="images">
<?php
	foreach ($images as $image) {
		$pi = pathinfo($image);
		$pi['filename'] = substr($pi['basename'], 0, strrpos($pi['basename'], '.')); 
		echo '
			<div class="pic"><a title="' . displayify($pi['filename']) . '" class="fancybox" rel="x" href="' . $conf['basedir'] . $conf['storage'] . '/' .
					$directory . $image . '">
				<img src="' . $conf['basedir'] . $conf['storage'] . '/' .
					$directory . $conf['thumbnail_prefix'] . $image . '" alt=""/>
			</a></div>';
	}
?>
<div class="clear"></div>
</div>

<div id="bottom">

<div id="byncsa">
<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/80x15.png" /></a>&nbsp;&nbsp;&nbsp;These photos are licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-Noncommercial-Share Alike 3.0 License</a>.
</div>

<div id="calibration">
<div class="cal26"></div>
<div class="cal25"></div>
<div class="cal24"></div>
<div class="cal23"></div>
<div class="cal22"></div>
<div class="cal21"></div>
<div class="cal20"></div>
<div class="cal19"></div>
<div class="cal18"></div>
<div class="cal17"></div>
<div class="cal16"></div>
<div class="cal15"></div>
<div class="cal14"></div>
<div class="cal13"></div>
<div class="cal12"></div>
<div class="cal11"></div>
<div class="cal10"></div>
<div class="cal09"></div>
<div class="cal08"></div>
<div class="cal07"></div>
<div class="cal06"></div>
<div class="cal05"></div>
<div class="cal04"></div>
<div class="cal03"></div>
<div class="cal02"></div>
<div class="cal01"></div>
<div class="clear"></div>
</div>

</div>

</body>
</html>
