<?php

namespace App\Model;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Updater
{
	const UPDATE_TIMEOUT = 30;
	const DIR_CHMOD = 0775;

	private $cache;
	private $image;

	public function __construct()
	{
		$this->cache = new FilesystemAdapter('updater');
		$this->image = new Image();
	}

	/**
	 * Create missing directories and cache unprocessed files
	 */
	public function update_dirs($source, $destination)
	{
		return $this->cache->get('updates', function($cacheItem) use ($source, $destination) {
			$cacheItem->expiresAfter(self::UPDATE_TIMEOUT);
			return $this->recursively_update_dir($source, $destination);
		});
	}

	/**
	 * Recursively create missing directories and return unprocessed files
	 */
	private function recursively_update_dir($source, $destination)
	{
		$updates = [];

		$src = new Directory($source);
		$dst = new Directory($destination);

		// create missing subdirectories
		foreach ($dst->missing($src, Directory::DIRS) as $missing) {
			$file = "$destination/$missing";
			mkdir($file);
			chmod($file, self::DIR_CHMOD);
		}

		// process subdirectories
		foreach ($src->get_dirs() as $dir) {
			$updates += $this->recursively_update_dir("$source/$dir", "$destination/$dir");
		}

		// check directories for missing images and thumbnails
		$dst_files = $dst->get_files();
		foreach ($src->get_files() as $src_file) {
			// source file
			$src_path = "$source/$src_file";

			// destination image and thumbnail
			foreach ([
				'image' => $src_file,
				'thumb' => Helpers::thumb($src_file),
			] as $type => $dst_file) {
				// destination doesn't exist
				if (! in_array($dst_file, $dst_files)) {
					// TODO optionally check timestamps:
					// filectime($dst_file) > filectime($src_path))

					$dst_path = $destination . '/' . $dst_file;
					$updates[$src_path][$type] = $dst_path;
				}
			}
		}

		return $updates;
	}

	/**
	 * Update a single file; relies on cache from update_dirs()
	 */
	public function update_file()
	{
		$cacheItem = $this->cache->getItem('updates');
		$updates = $cacheItem->get() ?? [];

		$orig = key($updates);
		$file = array_shift($updates);

		if (! $file) {
			// no more file, finish!
			$this->cache->delete('updates');
			return [];
		}

		$cacheItem->set($updates)->expiresAfter(self::UPDATE_TIMEOUT);
		$this->cache->save($cacheItem);

		return $this->image->generate($orig, $file);
	}
}
