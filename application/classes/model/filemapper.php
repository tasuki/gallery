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

		try {
			// Try to get destination directory, with all files
			$dst = new Model_Directory($destination);
			$dst_dirs_missing = $dst->missing($src, Model_Directory::DIRS);
			$dst_files = $dst->get_files();
		} catch (UnexpectedValueException $e) {
			// Destination dir doesn't exist, hence all src dirs
			// are missing from dst and there are no files there.
			$dst_dirs_missing = $src->get_dirs();
			$dst_files = array();
		}

		foreach ($dst_dirs_missing as $dir) {
			$this->dirs[] = array(
				'src' => "$source/$dir",
				'dst' => "$destination/$dir",
			);
		}

		// Get missing files
		foreach ($src->get_files() as $src_file) {
			// Source file
			$file = array('src' => "$source/$src_file");
			foreach ($mapping as $type => $prefix) {
				$dst_file = $prefix . $src_file;

				// Is destination file missing?
				if (! in_array($dst_file, $dst_files)) {
					$file[$type] = "$destination/$dst_file";
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
				"$source/$dir",
				"$destination/$dir",
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
