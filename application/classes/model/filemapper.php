<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Filemapper model
 *
 * @package  Gallery
 * @category Model
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Model_Filemapper
{
	/**
	 * @var  array  List of directories
	 */
	protected $dirs = array();

	/**
	 * @var  array  List of files
	 */
	protected $files = array();

	/**
	 * Create filemapper model
	 *
	 * @param  string  path to directory
	 */
	public function __construct($source, $destination, $mapping)
	{
		$this->recursively_process_dir($source, $destination, $mapping);
	}

	/**
	 * Recursively get missing directories and files
	 *
	 * @param  string  source directory
	 * @param  string  destination directory
	 */
	protected function recursively_process_dir($source, $destination, $mapping)
	{
		$src = new Model_Directory($source);
		$dst = new Model_Directory($destination);

		// Get missing dirs
		foreach ($dst->missing($src, Model_Directory::DIRS) as $dir) {
			$this->dirs[] = array(
				'src' => $src->child($dir),
				'dst' => $dst->child($dir),
			);
		}

		// Get missing files
		$dst_files = $dst->get_files();
		foreach ($src->get_files() as $src_file) {
			// Source file
			$file = array('src' => $src->child($src_file));
			foreach ($mapping as $type => $prefix) {
				$dst_file = $prefix . $src_file;

				// Is destination file missing?
				if (! in_array($dst_file, $dst_files)) {
					$file[$type] = $dst->child($dst_file);
				}
			}

			// If any destination is missing, add file
			if (count($file) > 1) {
				$this->files[] = $file;
			}
		}

		// Process subdirectories
		foreach ($src->get_dirs() as $dir) {
			$this->recursively_process_dir(
				$src->child($dir),
				$dst->child($dir),
				$mapping
			);
		}
	}

	/**
	 * Get next directory
	 *
	 * @return  array  directory
	 */
	public function next_dir()
	{
		return array_shift($this->dirs);
	}

	/**
	 * Get next file
	 *
	 * @return  array  file
	 */
	public function next_file()
	{
		return array_shift($this->files);
	}
}
