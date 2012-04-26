<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Add resizing capabilities to Kohana_Image
 *
 * @package   Gallery
 * @author    Vit 'tasuki' Brunner
 * @license   GPL
 */
abstract class Image extends Kohana_Image
{
	/**
	 * Fit image into a square box of a specified size
	 *
	 * Preserves the aspect ratio and fits the image into a box,
	 * so that the longer side of the image has size $size.
	 *
	 * @param  int  box size
	 */
	public function fit_into_box($size)
	{
		if ($this->width > $this->height) {
			// landscape
			$this->resize($size);
		} else {
			// portrait
			$this->resize(null, $size);
		}
	}

	/**
	 * Scale image so that it fits into a grid
	 *
	 * @param  int  base width of the grid
	 * @param  int  gap between items
	 */
	public function fit_into_grid($base_size, $gap)
	{
		if ($this->height * 1.61 > $this->width) {
			$this->resize($base_size);
		} else {
			$this->resize($base_size * 2 + $gap);
		}
	}

	/**
	 * Scale image so that its area is size^2
	 *
	 * @param  int  side of square with same area
	 */
	public function fit_area($size)
	{
		$area = $size * $size;
		$aspect = $this->height / $this->width;
		$new_width = sqrt($area / $aspect);

		$this->resize($new_width);
	}
}
