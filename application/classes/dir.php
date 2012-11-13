<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Directory
 *
 * @package  Gallery
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Dir
{
	// Bitfields for choosing items to get
	const FILES = 1;
	const DIRS  = 2;
	const ALL   = 3;

	/**
	 * @var  DirectoryIterator  The current directory
	 */
	protected $dir;

	/**
	 * Create directory
	 *
	 * @param  string  path to directory
	 */
	public function __construct($directory)
	{
		$this->dir = new RelaxedDirectoryIterator($directory);
	}

	/**
	 * Get path to a child of the directory
	 *
	 * @param   string  child name
	 * @return  string  path to child
	 */
	public function child($name)
	{
		return $this->dir->getPath() . DIRECTORY_SEPARATOR . $name;
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
	 * @param   int    bitfield Dir::FILES/DIRS/ALL
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
			if (($type & self::DIRS && $item->isDir()
				|| $type & self::FILES && $item->isFile())
				&& ! preg_match("/^(" . join('|', $filters) . ")/", $item)) {

				$items[] = (string) $item;
			}
		}

		sort($items);
		return $items;
	}

	/**
	 * Get neighboring directories of the current directory
	 *
	 * @return  array  neighbors of the current directory
	 */
	public function get_neighbors()
	{
		// get parent Dir and its children
		$path = $this->dir->getPath();
		$parent = new self(dirname($path));
		$dirs = $parent->get_dirs();

		// find position of current dir
		$key = array_search(basename($path), $dirs);

		// check if previous/next directories exist
		$neighbors = array();
		if (array_key_exists($key - 1, $dirs))
			$neighbors['prev'] = $dirs[$key - 1];
		if (array_key_exists($key + 1, $dirs))
			$neighbors['next'] = $dirs[$key + 1];

		return $neighbors;
	}

	/**
	 * Get items missing from current directory compared to another
	 *
	 * @param   Dir    directory to compare against
	 * @param   int    Dir::FILES/DIRS/ALL
	 * @return  array  missing items
	 */
	public function missing(self $dir, $type)
	{
		return array_values(array_diff(
			$dir->get_items($type),
			$this->get_items($type)
		));
	}
}