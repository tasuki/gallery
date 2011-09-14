<?php

include('config.php');
include('tasumbnail.php');

$thumbnailer = new tasumbnail();
$thumbnailer->setMethod($conf['thumbnail']['method']);
$thumbnailer->maxHeight = $conf['thumbnail']['maxheight'];
$thumbnailer->maxWidth = $conf['thumbnail']['maxwidth'];

$resizer = new tasumbnail();
$resizer->setMethod($conf['full_image']['method']);
$resizer->maxHeight = $conf['full_image']['maxheight'];
$resizer->maxWidth = $conf['full_image']['maxwidth'];


// remove undescores and dashes from text, replace with spaces
function displayify($text) {
	return preg_replace('/[_-]/', ' ', $text);
}

// returns array of files and folders in the passed folder
function getDir($directory) {
	global $conf;

	// get rid of extra dots & paranoia
	$dir = './' . $conf['storage'] . '/' . preg_replace('/\.\./', '.', $directory);
	$list = @scandir($dir);

	if ($list === false) // can't list the directory
		throw new Exception("can't open that");

	$files = array('directories' => array(), 'files' => array());
	foreach ($list as $item) {
		// skip current and parent directory
		if ($item == '.' || $item == '..') continue;

		if (is_dir($dir . $item)) // our item is directory
			$files['directories'][] = $item;
		else { // our item is regular file
			if (! preg_match('/' . $conf['thumbnail_prefix'] . '/', $item))
				$files['files'][] = $item;
		}
	}
	return $files;
}

// processes directory and all its subdirectories
function recursivelyProcessDir($directory) {
	global $conf, $resizer, $thumbnailer;

	$updir = "./{$conf['updir']}/{$directory}";
	$storage = "./{$conf['storage']}/{$directory}";

	$list = scandir($updir);
	$files = array('directories' => array(), 'files' => array());

	// loop through items in the directory
	foreach ($list as $item) {
		if ($item == '.' || $item == '..') continue;

		if (is_dir($updir . $item)) { // if it's a directory, check if exists and create it if it doesn't
			if (! is_dir($storage . $item)) {
				if (mkdir($storage . $item)) {
					if (chmod($storage . $item, 0777))
						echo '<p>created directory ' . $storage . $item . '</p>';
					else
						echo '<p class="fail">failed to chmod directory ' . $storage . $item . '</p>';
				} else echo '<p class="fail">failed to create directory ' . $storage . $item . '</p>';
			}
			recursivelyProcessDir($directory . $item . '/');

		} else {
			// create the downsized image
			if ((! is_file($storage . $item)) || (filectime($storage . $item) < filectime($updir . $item))) {
				$resizer->loadImage($updir . $item);
				$resizer->outputImage = $storage . $item;
				$resizer->rescale();
				chmod($storage . $item, 0666);
				echo '<p>creating image ' . $storage . $item . '</p>';
			}

			// create the thumbnail
			if ((! is_file($storage . $conf['thumbnail_prefix'] . $item))
					|| (filectime($storage . $conf['thumbnail_prefix'] . $item) < filectime($updir . $item))) {
				$thumbnailer->loadImage($updir . $item);
				$thumbnailer->outputImage = $storage . $conf['thumbnail_prefix'] . $item;
				$thumbnailer->rescale();
				chmod($storage . $conf['thumbnail_prefix'] . $item, 0666);
				echo '<p>creating thumbnail ' . $storage . $conf['thumbnail_prefix'] . $item . '</p>';
			}
		}
	}
}

// create array with get path
$get = explode('/', trim($_GET['q'], '/'));

switch ($get[0]) {
	case 'reload': // load batch of unprocessed images from upload directory
		recursivelyProcessDir('');
		echo "<p>---FINISHED---</p>";
		die;

	case 'update': // update the whole gallery
		include('update-gallery.php');
		break;

	default: // display gallery

		$directory = implode('/', $get) . '/';
		try {
			$dir = getDir($directory);
			$directories = $dir['directories'];
			$images = $dir['files'];

			$tmp = $conf['basedir'] . '/';
			$crumbs = array(array($tmp, $conf['name']));
			foreach ($get as $part) {
				$tmp .= $part;
				$crumbs[] = array($tmp, $part);
				$tmp .= '/';
			}

			$last = array_pop($crumbs);
			$breadcrumbs = array();
			foreach ($crumbs as $crumb) {
				list($url, $title) = $crumb;
				$title = displayify($title);

				$breadcrumbs[] = '<a href="' . $url . '">' . $title . '</a>';
				$title .= " &ndash; {$conf['name']}";
			}
			list($url, $title) = $last;
			$title = displayify($title);
			if ($title) {
				$breadcrumbs[] = "<span class='crumb'>$title</span>";
				$title .= " &ndash; {$conf['name']}";
			} else {
				$title = $conf['name'];
			}

			$breadcrumb = implode(' &raquo; ', $breadcrumbs);
			if ($directory === '/') $directory = '';

		} catch(Exception $e) {
			$directories = array();
		}

		include('layout.php');
}

?>
