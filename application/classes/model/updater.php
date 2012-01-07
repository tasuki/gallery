<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Updater model
 *
 * @package  Gallery
 * @category Model
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Model_Updater
{
	public $updates;

	protected $prefix;
	protected $dir_chmod;
	protected $file_chmod;

	/**
	 * Preload configs
	 *
	 * @param  Config_Group  thumbnail and chmod settings
	 */
	public function __construct(Config_Group $settings)
	{
		$this->prefix     = Arr::get($settings->get('thumbnail'), 'prefix');
		$this->dir_chmod  = Arr::get($settings->get('chmod'), 'dir');
		$this->file_chmod = Arr::get($settings->get('chmod'), 'file');
	}

	/**
	 * Recursively create missing directories and save changed files
	 *
	 * @param  string  source directory
	 * @param  string  destination directory
	 */
	public function update_dir($source, $destination)
	{
		$src = new Model_Directory($source);
		$dst = new Model_Directory($destination);

		// create missing subdirectories
		foreach ($dst->missing($src, Model_Directory::DIRS) as $missing) {
			$file = $destination . '/' . $missing;
			mkdir($file);
			chmod($file, $this->dir_chmod);
		}

		// process subdirectories
		foreach ($src->get_dirs() as $dir) {
			$this->update_dir("$source/$dir", "$destination/$dir");
		}

		// check directories for missing images and thumbnails
		$dst_files = $dst->get_files();
		foreach ($src->get_files() as $src_file) {
			// source file
			$src_path = $source . '/' . $src_file;

			// destination image and thumbnail
			foreach (array(
				'image' => $src_file,
				'thumb' => $this->prefix . $src_file,
			) as $type => $dst_file) {
				$dst_path = $destination . '/' . $dst_file;

				// destination doesn't exist
				if (! in_array($dst_file, $dst_files)) {
					// TODO optionally check timestamps:
					// filectime($dst_file) > filectime($src_path))

					$this->updates[$src_path][$type] = $dst_path;
				}
			}
		}
	}

	/**
	 * Update a file
	 */
	protected function update_file($src, $type, $dst)
	{
		// TODO
	}
}
