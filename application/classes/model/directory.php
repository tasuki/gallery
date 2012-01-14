<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Directory model
 *
 * @package  Gallery
 * @category Model
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Model_Directory
{
	// Bitfields for choosing items to get
	const FILES = 1;
	const DIRS  = 2;
	const ALL   = 3;

	/**
	 * @var  DirectoryIterator  The current directory
	 */
	public $dir;

	/**
	 * Create directory model
	 *
	 * @param  string  path to directory
	 */
	public function __construct($directory)
	{
		$this->dir = new DirectoryIterator($directory);
	}

	/**
	 * Get subdirectories of current directory
	 *
	 * @param   array  patterns to filter
	 * @return  array  subdirectories
	 */
	public function get_dirs($filters = array())
	{
		return $this->get_items(self::DIRS, $filters);
	}

	/**
	 * Get files from current directory
	 *
	 * @param   array  patterns to filter
	 * @return  array  files
	 */
	public function get_files($filters = array())
	{
		return $this->get_items(self::FILES, $filters);
	}

	/**
	 * Get items from directory that pass check function
	 *
	 * @param   int    bitfield Model_Directory::FILES/DIRS/ALL
	 * @param   array  items to skip when match at beginning
	 * @return  array  items from directory
	 */
	public function get_items($type, $filters = array())
	{
		// start from the beginning
		$this->dir->rewind();
		$items = array();

		// always filter dot files
		$filters[] = ".";

		// escape filters
		foreach ($filters as $key => $filter)
			$filters[$key] = preg_quote($filter, '/');

		foreach ($this->dir as $item) {
			// (want directories and is a directory
			//     OR want files and is a file)
			// AND doesn't match filters
			if (($type & Model_Directory::DIRS && $item->isDir()
				|| $type & Model_Directory::FILES && $item->isFile())
				&& ! preg_match("/^(" . join('|', $filters) . ")/", $item)) {

				$items[] = (string) $item;
			}
		}

		sort($items);
		return $items;
	}

	/**
	 * Get items missing from current directory compared to another
	 *
	 * @param   Model_Directory  directory to compare against
	 * @param   int              Model_Directory::FILES/DIRS/ALL
	 * @return  array            missing items
	 */
	public function missing(Model_Directory $dir, $type)
	{
		return array_diff($dir->get_items($type), $this->get_items($type));
	}
}
