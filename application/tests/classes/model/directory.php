<?php defined('SYSPATH') or die('No direct access allowed!');

/**
 * Directory model test
 *
 * @package  Gallery
 * @category Test
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Model_DirectoryTest extends Kohana_UnitTest_TestCase
{
	/**
	 * Test file getting
	 */
	public function test_get_files()
	{
		$filters = array('rose');
		$expected = array('daisies.jpg', 'lilies.jpg');
		$directory = new Model_Directory('test_data/plants');

		$this->assertEquals($expected, $directory->get_files($filters));
		$this->assertEquals($expected, $directory->get_items(
			Model_Directory::FILES, $filters
		));
	}

	/**
	 * Test dir getting
	 */
	public function test_get_dirs()
	{
		$filters = array('flo');
		$expected = array('algae', 'mosses', 'trees');
		$directory = new Model_Directory('test_data/plants');

		$this->assertEquals($expected, $directory->get_dirs($filters));
		$this->assertEquals($expected, $directory->get_items(
			Model_Directory::DIRS, $filters
		));
	}

	/**
	 * Test getting all items
	 */
	public function test_get_items()
	{
		$filters = array('flo');
		$expected = array('algae', 'daisies.jpg',
			'lilies.jpg', 'mosses', 'roses.jpg', 'trees');
		$directory = new Model_Directory('test_data/plants');

		$this->assertEquals($expected, $directory->get_items(
			Model_Directory::ALL, $filters
		));
	}

	/**
	 * Test getting neighbors
	 *
	 * @param  array   expected result
	 * @param  string  dir to get neighbors for
	 *
	 * @dataProvider  provide_get_neighbors
	 */
	public function test_get_neighbors(array $expected, $directory)
	{
		$directory = new Model_Directory('test_data/' . $directory);
		$this->assertEquals($expected, $directory->get_neighbors());
	}

	/**
	 * Provider for test_get_neighbors
	 */
	public function provide_get_neighbors()
	{
		return array(
			// first
			array(
				array('next' => 'flowers'),
				'plants/algae',
			),

			// middle
			array(
				array(
					'prev' => 'flowers',
					'next' => 'trees',
				),
				'plants/mosses',
			),

			// last
			array(
				array('prev' => 'mosses'),
				'plants/trees',
			),
		);
	}

	/**
	 * Test getting missing items
	 */
	public function test_missing()
	{
		$first  = new Model_Directory('test_data/plants');
		$second = new Model_Directory('test_data/plants/flowers');

		$this->assertSame(
			array('lotus.jpg'),
			$first->missing($second, Model_Directory::FILES)
		);

		$this->assertEquals(
			array('lilies.jpg', 'roses.jpg'),
			$second->missing($first, Model_Directory::FILES)
		);
	}
}
