<?php defined('SYSPATH') or die('No direct access allowed!');

/**
 * Image test
 *
 * @package  Gallery
 * @category Test
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Model_ImageTest extends Kohana_UnitTest_TestCase
{
	/**
	 * Get test image, 100px wide 50px tall
	 *
	 * @return  Image
	 */
	public function get_landscape()
	{
		return Image::factory('test_data/landscape.jpg');
	}

	/**
	 * Get test image, 50px wide 100px tall
	 *
	 * @return  Image
	 */
	public function get_portrait()
	{
		return Image::factory('test_data/portrait.png');
	}

	/**
	 * Test Image::fit_into_box() for portrait
	 */
	public function test_portrait_fit_into_box()
	{
		$img = $this->get_portrait();
		$img->fit_into_box(30);
		$this->assertEquals($img->width,  15);
		$this->assertEquals($img->height, 30);
	}

	/**
	 * Test Image::fit_into_box() for landscape
	 */
	public function test_landscape_fit_into_box()
	{
		$img = $this->get_landscape();
		$img->fit_into_box(40);
		$this->assertEquals($img->width,  40);
		$this->assertEquals($img->height, 20);
	}

	/**
	 * Test Image::resize() upscaling
	 */
	public function test_upscaling()
	{
		$img = $this->get_landscape();

		$img->upscale = true;
		$img->fit_into_box(200);
		$this->assertEquals($img->width,  200);
		$this->assertEquals($img->height, 100);

		$img->upscale = false;
		$img->fit_into_box(400);
		$this->assertEquals($img->width,  200);
		$this->assertEquals($img->height, 100);
	}

	/**
	 * Test Image::fit_into_grid() for landscape
	 */
	public function test_landscape_fit_into_grid()
	{
		$img = $this->get_landscape();
		$img->fit_into_grid(40, 10);
		$this->assertEquals($img->width,  90);
		$this->assertEquals($img->height, 45);
	}

	/**
	 * Test Image::fit_into_grid() for portrait
	 */
	public function test_portrait_fit_into_grid()
	{
		$img = $this->get_portrait();
		$img->fit_into_grid(40, 10);
		$this->assertEquals($img->width,  40);
		$this->assertEquals($img->height, 80);
	}

	/**
	 * Test Image::fit_area()
	 */
	public function test_fit_area()
	{
		$img = $this->get_landscape();
		$img->fit_area(10);
		$this->assertEquals($img->width, 14);
		$this->assertEquals($img->height, 7);
	}
}
