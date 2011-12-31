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
	 * Get items from directory that pass check function
	 *
	 * @param   function  skip item?
	 * @return  array     items from directory
	 */
	protected function get_items($check_function)
	{
		$items = array();
		$this->dir->rewind();

		foreach ($this->dir as $item) {
			if ($check_function($item))
				continue;

			$items[] = (string) $item;
		}

		sort($items);
		return $items;
	}

	/**
	 * Get subdirectories of current directory
	 *
	 * @return  array  subdirectories
	 */
	public function get_dirs()
	{
		return $this->get_items(function($item) {
			return (! $item->isDir() || $item->isDot());
		});
	}

	/**
	 * Get files from current directory
	 *
	 * @return  array  files
	 */
	public function get_files()
	{
		return $this->get_items(function($item) {
			$match = preg_quote(Arr::get(Kohana::$config->load('settings')
				->get('thumbnail'), 'prefix'), '/');

			return (! $item->isFile() || preg_match("/^$match/", $item));
		});
	}
}
