<?php defined('SYSPATH') or die('No direct access allowed!');

/**
 * RelaxedDirectoryIterator test
 *
 * @package  Gallery
 * @category Test
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class RelaxedDirectoryIteratorTest extends Kohana_UnitTest_TestCase
{
	/**
	 * Test RelaxedDirectoryIterator
	 *
	 * Test that iterator works on both an existing directory
	 * and a fake one.
	 *
	 * @param  string  directory to test
	 * @param  bool    has next entry?
	 * @dataProvider   provide_test_iterator
	 */
	public function test_iterator($path, $valid)
	{
		$iterator = new RelaxedDirectoryIterator($path);
		$iterator->next();

		$this->assertEquals($iterator->getPath(), $path);
		$this->assertEquals($iterator->current(), $iterator);
		$this->assertEquals($iterator->valid(), $valid);
	}

	/**
	 * Provider for test_iterator
	 *
	 * @return  array
	 */
	public function provide_test_iterator()
	{
		return array(
			// Existing directory
			array(
				'path'  => 'test_data/plants',
				'valid' => true,
			),

			// Nonexistent directory
			array(
				'path'  => 'test_data/fake',
				'valid' => false,
			),
		);
	}
}
