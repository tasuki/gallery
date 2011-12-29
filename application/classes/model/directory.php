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
	 * @param  array  parts of url that lead to directory
	 */
	public function __construct(array $url_parts)
	{
		$gallery_dir = Kohana::$config->load('application.dir.gallery');
		$base = DOCROOT . $gallery_dir . '/';
		$this->dir = new DirectoryIterator($base . implode('/', $url_parts));
	}

	/**
	 * Get subdirectories of current directory
	 *
	 * @return  array  subdirectories
	 */
	public function get_dirs()
	{
		$dirs = array();
		$this->dir->rewind();

		foreach ($this->dir as $dir) {
			if (! $dir->isDir() || $dir->isDot())
				continue;

			$dirs[] = (string) $dir;
		}

		sort($dirs);
		return $dirs;
	}

	/**
	 * Get files from current directory
	 *
	 * @return  array  files
	 */
	public function get_files()
	{
		$files = array();
		$this->dir->rewind();

		$prefix = Kohana::$config->load('settings.thumbnail.prefix');
		$regex = preg_quote($prefix, '/');

		foreach ($this->dir as $file) {
			if (! $file->isFile() || preg_match("/$regex/", $file))
				continue;

			$files[] = (string) $file;
		}

		sort($files);
		return $files;
	}
}
