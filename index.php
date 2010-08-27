<?php

include('config.php');
include('tasumbnail.php');

$thumbnailer = new tasumbnail();
$thumbnailer->setMethod($conf['thumbnail']['method']);
$thumbnailer->setMaxHeight($conf['thumbnail']['maxheight']);
$thumbnailer->setMaxWidth($conf['thumbnail']['maxwidth']);

$resizer = new tasumbnail();
$resizer->setMethod($conf['full_image']['method']);
$resizer->setMaxHeight($conf['full_image']['maxheight']);
$resizer->setMaxWidth($conf['full_image']['maxwidth']);


function displayify($text) {
	// remove undescores and dashes from file names, replace with spaces
	return preg_replace('/[_-]/', ' ', $text);
}

function getDir($directory) {
	global $conf;

	$dir = './' . $conf['storage'] . '/' . preg_replace('/\.\./', '.', $directory);
	$list = @scandir($dir);

	if ($list === false)
		throw new Exception("can't open that");

	$files = array('directories' => array(), 'files' => array());
	foreach ($list as $item) {
		if ($item == '.' || $item == '..') continue;
		if (is_dir($dir . $item))
			$files['directories'][] = $item;
		else {
			if (! preg_match('/' . $conf['thumbnail_prefix'] . '/', $item))
				$files['files'][] = $item;
		}
	}
	return $files;
}

function getConfig($directory) {
	global $conf;
}

function recursivelyProcessDir($directory, $config = array()) {
	global $conf, $resizer, $thumbnailer;

	$updir = "./{$conf['updir']}/{$directory}";
	$storage = "./{$conf['storage']}/{$directory}";

	$list = scandir($updir);
	$files = array('directories' => array(), 'files' => array());

	foreach ($list as $item) {
		if ($item == '.' || $item == '..') continue;
		if (is_dir($updir . $item)) {
			if (! is_dir($storage . $item)) {
				if (mkdir($storage . $item)) {
					if (chmod($storage . $item, 0777))
						echo '<p>created directory ' . $storage . $item . '</p>';
					else echo '<p class="fail">failed to chmod directory ' . $storage . $item . '</p>';
				} else echo '<p class="fail">failed to create directory ' . $storage . $item . '</p>';
			}
			recursivelyProcessDir($directory . $item . '/');
		}
		else {
			if ((! is_file($storage . $item)) || (filectime($storage . $item) < filectime($updir . $item))) {
				$resizer->loadImage($updir . $item);
				$resizer->setOutputImage($storage . $item);
				$resizer->rescale();
				chmod($storage . $item, 0666);
				echo '<p>creating image ' . $storage . $item . '</p>';
			}
			if ((! is_file($storage . $conf['thumbnail_prefix'] . $item))
					|| (filectime($storage . $conf['thumbnail_prefix'] . $item) < filectime($updir . $item))) {
				$thumbnailer->loadImage($updir . $item);
				$thumbnailer->setOutputImage($storage . $conf['thumbnail_prefix'] . $item);
				$thumbnailer->rescale();
				chmod($storage . $conf['thumbnail_prefix'] . $item, 0666);
				echo '<p>creating thumbnail ' . $storage . $conf['thumbnail_prefix'] . $item . '</p>';
			}
		}
	}
}

$get = explode('/', $_GET['q']);
if (end($get) != '') $get[] = '';

if ($get[0] == 'reload') {
	recursivelyProcessDir('');
	echo "<p>---FINISHED---</p>";
	die();

} elseif ($get[0] == 'update') {
	include('update-gallery.php');

} else {
	$tmp = $conf['basedir'];
	$breadcrumb = '<a href="' . $tmp . '">' . $conf['name'] . '</a>';

	$directory = implode('/', $get);
	try {
		$dir = getDir($directory);
		$directories = $dir['directories'];
		$images = $dir['files'];

		foreach ($get as $part) {
			if ($part) {
				$tmp .= $part;
				$breadcrumb .= ' &raquo; <a href="' . $tmp . '">' . displayify($part) . '</a>';
				$tmp .= '/';
			}
		}
	} catch(Exception $e) {
		$directories = array();
	}

	include('layout.php');

}

?>
