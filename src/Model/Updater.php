<?php

namespace App\Model;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Metadata\ExifMetadataReader;
use App\Controller\Helpers;

class Updater
{
	const UPDATE_TIMEOUT = 30;
	const FILE_CHMOD = 0664;
	const DIR_CHMOD = 0775;

	protected $cache;
	protected $imagine;

	/**
	 * Make sure we're unique
	 */
	public function __construct()
	{
		$this->cache = new FilesystemAdapter('updater');
		$this->imagine = new Imagine();
	}

	/**
	 * Create missing directories and cache unprocessed files
	 */
	public function update_dirs($source, $destination)
	{
		return $this->cache->get('updates', function(ItemInterface $item) use ($source, $destination) {
			return $this->recursively_update_dir($source, $destination);
		});
	}

	/**
	 * Recursively create missing directories and return unprocessed files
	 */
	protected function recursively_update_dir($source, $destination)
	{
		$updates = [];

		$src = new Directory($source);
		$dst = new Directory($destination);

		// create missing subdirectories
		foreach ($dst->missing($src, Directory::DIRS) as $missing) {
			$file = $destination . '/' . $missing;
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
			$src_path = $source . '/' . $src_file;

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
	 * Update a file; relies on cache from update_dirs()
	 */
	public function update_file()
	{
		// get list of files to update
		$updates = $this->cache->get('updates', function (ItemInterface $item) { return []; });

		$orig = key($updates);
		$file = array_shift($updates);

		if (! $file) {
			// no more file, finish!
			$this->cache->delete('updates');
			return [];
		}

		$this->cache->delete('updates');
		$this->cache->get('updates', function(ItemInterface $item) use ($updates) { return $updates; });

		return $this->resize($orig, $file);
	}

	private function resize($orig, $file)
	{
		// create image from original
		$img = $this->imagine
			->setMetadataReader(new ExifMetadataReader())
			->open($orig);

		if (array_key_exists('image', $file)) {
			$this->resizeTo($img, $file['image'], 3072, 3072);
		}

		if (array_key_exists('thumb', $file)) {
			$this->resizeTo($img, $file['thumb'], 600, 300);
		}

		return $file;
	}

	private function resizeTo($img, $file, $width, $height)
	{
		$metadata = $img->metadata();
		$iwidth = $metadata['computed.Width'];
		$iheight = $metadata['computed.Height'];
		$saveOptions = [ 'jpeg_quality' => 85, 'webp_quality' => 85 ];

		$ratio = $iwidth / $iheight;

		if ($width / $height > $ratio) {
			$width = $height * $ratio;
		} else {
			$height = $width / $ratio;
		}

		if ($width > $iwidth || $height > $iheight) {
			$img->save($file, $saveOptions);
		} else {
			$img->resize(new Box($width, $height), ImageInterface::FILTER_LANCZOS)
				->save($file, $saveOptions);
		}

		chmod($file, self::FILE_CHMOD);
	}
}
