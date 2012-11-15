<?php defined('SYSPATH') or die('No direct access allowed!');

/**
 * Filemapper test
 *
 * @package  Gallery
 * @category Test
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Model_FilemapperTest extends Kohana_UnitTest_TestCase
{
	/**
	 * Test Filemapper with real filesystem (tm)
	 */
	public function test_mapping()
	{
		$mapper = new Model_Filemapper(
			'test_data/plants',
			'test_data/processed_plants',
			array(
				'orig'   => '',
				'prefix' => '__',
			)
		);

		$dirs = array(
			array(
				'src' => 'test_data/plants/flowers',
				'dst' => 'test_data/processed_plants/flowers',
			),
			array(
				'src' => 'test_data/plants/trees',
				'dst' => 'test_data/processed_plants/trees',
			),
			array(
				'src' => 'test_data/plants/trees/jehličnaté',
				'dst' => 'test_data/processed_plants/trees/jehličnaté',
			),
			null, // no more dirs
		);
		foreach ($dirs as $dir) {
			$this->assertEquals($mapper->next_dir(), $dir);
		}

		$files = array(
			array(
				'src'    => 'test_data/plants/daisies.jpg',
				'prefix' => 'test_data/processed_plants/__daisies.jpg',
			),
			array(
				'src'    => 'test_data/plants/lilies.jpg',
				'orig'   => 'test_data/processed_plants/lilies.jpg',
			),
			array(
				'src'    => 'test_data/plants/algae/algae.jpg',
				'prefix' => 'test_data/processed_plants/algae/__algae.jpg',
				'orig'   => 'test_data/processed_plants/algae/algae.jpg',
			),
		);
		foreach ($files as $file) {
			$this->assertEquals($mapper->next_file(), $file);
		}
	}
}
