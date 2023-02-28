<?php

namespace App\Model;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Metadata\ExifMetadataReader;

class Updater
{
	const UPDATE_TIMEOUT = 30;
	const FILE_CHMOD = 0664;
	const DIR_CHMOD = 0775;

	public $updates = [];
	public $key;

	protected $cache;
	protected $prefix;
	protected $imagine;

	/**
	 * Make sure we're unique
	 */
	public function __construct($key = null, $prefix = "__")
	{
		$this->prefix = $prefix;
		$this->cache = new FilesystemAdapter('updater');
		$this->imagine = new Imagine();

		// get lock or create new
		$updateKey = $this->cache->get('update_underway', function(ItemInterface $item) {
			$item->expiresAfter(self::UPDATE_TIMEOUT);
			return md5(time());
		});

		if ($key && $key !== $updateKey) {
			// locks don't match
			throw new Exception('Another update is underway!');
		}

		$this->key = $updateKey;

		// refresh timeout
		$this->cache->delete('update_underway');
		$this->cache->get('update_underway', function(ItemInterface $item) {
			$item->expiresAfter(self::UPDATE_TIMEOUT);
			return $this->key;
		});
	}

	/**
	 * Create missing directories and cache unprocessed files
	 */
	public function update_dirs($source, $destination)
	{
		$this->recursively_update_dir($source, $destination);
		$this->cache->delete('updates');
		$this->cache->get('updates', function(ItemInterface $item) { return $this->updates; });
	}

	/**
	 * Recursively create missing directories and save unprocessed files
	 *
	 * @param  string  source directory
	 * @param  string  destination directory
	 */
	protected function recursively_update_dir($source, $destination)
	{
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
			$this->recursively_update_dir("$source/$dir", "$destination/$dir");
		}

		// check directories for missing images and thumbnails
		$dst_files = $dst->get_files();
		foreach ($src->get_files() as $src_file) {
			// source file
			$src_path = $source . '/' . $src_file;

			// destination image and thumbnail
			foreach ([
				'image' => $src_file,
				'thumb' => $this->prefix . $src_file,
			] as $type => $dst_file) {
				// destination doesn't exist
				if (! in_array($dst_file, $dst_files)) {
					// TODO optionally check timestamps:
					// filectime($dst_file) > filectime($src_path))

					$dst_path = $destination . '/' . $dst_file;
					$this->updates[$src_path][$type] = $dst_path;
				}
			}
		}
	}

	/**
	 * Update a file; relies on cache from update_dirs()
	 */
	public function update_file()
	{
		// get list of files to update
		$this->updates = $this->cache->get('updates', function (ItemInterface $item) { return []; });

		$orig = key($this->updates);
		$file = array_shift($this->updates);

		$this->cache->delete('updates');
		$this->cache->get('updates', function(ItemInterface $item) { return $this->updates; });

		if (! $file) {
			// no more file, finish!
			$this->cache->delete('updates');
			$this->cache->delete('update_underway');
			return [];
		}

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
			$this->resizeTo($img, $file['thumb'], 500, 300);
		}

		return $file;
	}

	private function resizeTo($img, $file, $width, $height)
	{
		$metadata = $img->metadata();
		$iwidth = $metadata['computed.Width'];
		$iheight = $metadata['computed.Height'];

		$ratio = $iwidth / $iheight;

		if ($width / $height > $ratio) {
			$width = $height * $ratio;
		} else {
			$height = $width / $ratio;
		}

		if ($width > $iwidth || $height > $iheight) {
			$img->save($file);
		} else {
			$img->resize(new Box($width, $height), ImageInterface::FILTER_LANCZOS)
				->save($file);
		}

		chmod($file, self::FILE_CHMOD);
	}
}
