<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Resizer model
 *
 * @package   Gallery
 * @category  Model
 * @author    Vit 'tasuki' Brunner
 * @license   GPL
 */
class Model_Resizer
{
	/**
	 * Fit image into a square box of a specified size
	 *
	 * Takes the image, preserves the aspect ration, and fits it into
	 * a box, so that the longer side of the image has size $size.
	 *
	 * @param  Image  image
	 * @param  int    box size
	 */
	public static function fit_into_box(Image $image, $size)
	{
		if ($image->width > $image->height) {
			// landscape
			$image->resize($size);
		} else {
			// portrait
			$image->resize(null, $size);
		}
	}

	/**
	 * Scale image so that it fits into a grid
	 */
	public static function fit_into_grid(Image $image, $base_size)
	{
		if ($image->height > $image->width || true) {
			$image->resize($base_size);
		} else {
			$image->resize($base_size * 2);
		}
	}
}
