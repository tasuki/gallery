<?php defined('SYSPATH') or die('No direct access allowed!');

/**
 * Directory test
 *
 * @package  Gallery
 * @category Test
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class DirTest extends Kohana_UnitTest_TestCase
{
	/**
	 * Test file getting
	 */
	public function test_get_files()
	{
		$filters = array('rose');
		$expected = array('daisies.jpg', 'lilies.jpg');
		$directory = new Dir('test_data/plants');

		$this->assertEquals($expected, $directory->get_files($filters));
		$this->assertEquals($expected, $directory->get_items(
			Dir::FILES, $filters
		));
	}

	/**
	 * Test dir getting
	 */
	public function test_get_dirs()
	{
		$filters = array('flo');
		$expected = array('algae', 'mosses', 'trees');
		$directory = new Dir('test_data/plants');

		$this->assertEquals($expected, $directory->get_dirs($filters));
		$this->assertEquals($expected, $directory->get_items(
			Dir::DIRS, $filters
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
		$directory = new Dir('test_data/plants');

		$this->assertEquals($expected, $directory->get_items(
			Dir::ALL, $filters
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
		$directory = new Dir('test_data/' . $directory);
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
		$first  = new Dir('test_data/plants');
		$second = new Dir('test_data/plants/flowers');

		$this->assertSame(
			array('lotus.jpg'),
			$first->missing($second, Dir::FILES)
		);

		$this->assertEquals(
			array('lilies.jpg', 'roses.jpg'),
			$second->missing($first, Dir::FILES)
		);
	}

	/**
	 * UTF is healthy for your skin
	 */
	public function test_utf_named_files()
	{
		$expected = array('bříza.jpg', 'jehličnaté');
		$dir = new Dir('test_data/plants/trees');

		$this->assertEquals($expected, $dir->get_items(Dir::ALL));
	}
}
