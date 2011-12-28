<?php

/**
 * Create various types of thumbnails.
 *
 * @author     Vit 'tasuki' Brunner
 * @license    GNU GPL
 * @version    0.001
 */
class tasumbnail
{
	private $origImage = null;
	private $origFormat;

	public $outputImage = null;
	public $maxWidth = null;
	public $maxHeight = null;
	public $doNotEnlarge = true;
	private $outputFormat = null;
	private $jpegQuality = 85;
	private $allowedFormats = array(
		'gif' => array('gif'),
		'jpeg' => array('jpg', 'jpeg'),
		'png' => array('png'),
		'wbmp' => array('bmp', 'wbmp'),
		'xbm' => array('xbm'),
		'xpm' => array('xpm'),
	);
	private $allowedMethods = array(
		'deform',
		'scale',
		'crop',
	);
	private $method = 'scale';

	public function setMethod($method) {
		if (in_array($method, $this->allowedMethods))
			$this->method = $method;
		else throw new Exception("Unknown method '$method'");
	}

	public function setOutputFormat($fileFormat) {
		$allowedInputSuffix = array();
		foreach ($this->allowedFormats as $suffixes) {
			foreach ($suffixes as $suffix) {
				$allowedInputSuffix[] = $suffix;
			}
		}

		if (! in_array($fileFormat, $allowedInputSuffix)) {
			throw new Exception("Unknown input file format/suffix '$fileFormat'.");
			return false;
		}

		foreach ($this->allowedFormats as $format => $suffixes) {
			if (in_array($fileFormat, $suffixes)) {
				$this->outputFormat = $format; // get file format from suffix
				return true;
			}
		}
		return false;
	}

	public function setJpegQuality($quality = 85)
	{
		if (is_int($quality) && $quality > 0 && $quality <= 100)
			$this->jpegQuality = $quality;
		else
			throw new Exception("JPEG Quality needs to be an int between 0 and 100.");
	}

	public function loadImage($location, $fileFormat = null)
	{
		if ($fileFormat == null) {
			// try to guess file format from the suffix
			$pathinfo = pathinfo($location);
			$fileFormat = strtolower($pathinfo['extension']);
			//$fileFormat = preg_replace('!^.*\.([^\.]*)$!', '\1', $location);
		}

		$allowedInputSuffix = array();
		foreach ($this->allowedFormats as $suffixes) {
			foreach ($suffixes as $suffix) {
				$allowedInputSuffix[] = $suffix;
			}
		}

		if (! in_array($fileFormat, $allowedInputSuffix)) {
			throw new Exception("Unknown input file format/suffix '$fileFormat'.");
			return;
		}

		foreach ($this->allowedFormats as $format => $suffixes) {
			if (in_array($fileFormat, $suffixes)) {
				$this->origFormat = $format; // get file format from suffix
				break;
			}
		}

		if ($this->outputFormat == null) $this->outputFormat = $this->origFormat;

		$this->origImage = $location;
	}

	public function rescale()
	{
		if ($this->origImage == null) {
			throw new Exception("You must load an image before rescaling.");
			return;
		}

		if (! is_file($this->origImage)) {
			throw new Exception("{$this->origImage} doesn't appear to be a file.");
			return;
		}

		eval('$src = imagecreatefrom' . $this->origFormat . '("' . $this->origImage . '");');

		list($width, $height) = getimagesize($this->origImage);

		if ($this->doNotEnlarge
			&& $width  < $this->maxWidth
			&& $height < $this->maxHeight) {

			$this->output($src);
			return;
		}

		$x_ratio = $this->maxWidth / $width;
		$y_ratio = $this->maxHeight / $height;

		$src_x = 0;
		$src_y = 0;
		$src_width = $width;
		$src_height = $height;

		$new_width = $width;
		$new_height = $height;

		// deform the picture into exact size, preserve dimensions which are not set
		if ($this->method == 'deform') {

			if ($this->maxWidth != null)
				$new_width = $this->maxWidth;

			if ($this->maxHeight != null)
				$new_height = $this->maxHeight;
		}
		// scale preserving the aspect ratio so as to fit both maxWidth and maxHeight, if set
		else if ($this->method == 'scale') {

			if ($this->maxWidth != null && $this->maxHeight != null) {
				if (($x_ratio * $height) < $this->maxHeight) {
					$new_width = $this->maxWidth;
					$new_height = ceil($x_ratio * $height);
				}
				else {
					$new_height = $this->maxHeight;
					$new_width = ceil($y_ratio * $width);
				}
			}
			else if ($this->maxWidth != null) {
				$new_width = $this->maxWidth;
				$new_height = ceil($x_ratio * $height);
			}
			else if ($this->maxHeight != null) {
				$new_height = $this->maxHeight;
				$new_width = ceil($y_ratio * $width);
			}
		} // scale preserving the aspect ratio and crop to size
		else if ($this->method == 'crop') {

			if ($this->maxWidth != null && $this->maxHeight != null) {
				$new_width = $this->maxWidth;
				$new_height = $this->maxHeight;

				if (($x_ratio * $height) < $this->maxHeight) {
					$src_width = ($this->maxWidth / $y_ratio);
					$src_x = ($width - $src_width) / 2;
				}
				else {
					$src_height = ($this->maxHeight / $x_ratio);
					$src_y = ($height - $src_height) / 2;
				}
			}
			else if ($this->maxWidth != null) {
				$new_width = $this->maxWidth;
				$new_height = ceil($x_ratio * $height);
			}
			else if ($this->maxHeight != null) {
				$new_width = ceil($y_ratio * $width);
				$new_height = $this->maxHeight;
			}
		}

		$dst = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($dst, $src, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_width, $src_height);

		$this->output($dst);
	}

	private function output($dst)
	{
		if ($this->outputImage == null) {
			header('Content-type: image/' . $this->outputFormat);
			if ($this->outputFormat == 'jpeg')
				eval('image' . $this->outputFormat . '($dst, NULL, ' . $this->jpegQuality . ');');
			else
				eval('image' . $this->outputFormat . '($dst);');
		} else {
			if ($this->outputFormat == 'jpeg')
				eval('image' . $this->outputFormat . '($dst, "' . $this->outputImage . '", ' . $this->jpegQuality . ');');
			else
				eval('image' . $this->outputFormat . '($dst, "' . $this->outputImage . '");');
		}
	}
}

?>
