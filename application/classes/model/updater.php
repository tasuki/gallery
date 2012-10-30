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
	const UPDATE_TIMEOUT = 30;

	public $updates = array();
	public $key;

	protected $cache;

	protected $prefix;
	protected $dir_chmod;

	/**
	 * Make sure we're unique
	 *
	 * @param  string  key
	 */
	public function __construct($key = null)
	{
		$this->key = $key;
		$this->cache = Cache::instance();

		// check lock
		$update = $this->cache->get('update_underway');

		if ($update === null) {
			// if first update, set lock
			$this->key = md5(time());
		} else if ($update !== $key) {
			// locks don't match
			throw new Exception('Another update is underway!');
		}

		// set or refresh lock timeout
		$this->cache->set('update_underway', $this->key, self::UPDATE_TIMEOUT);
	}

	/**
	 * Create missing directories and cache unprocessed files
	 *
	 * @param  Config_Group  settings
	 * @param  string        source directory
	 * @param  string        destination directory
	 */
	public function update_dirs(Config_Group $settings, $source, $destination)
	{
		$this->prefix    = Arr::get($settings->get('thumb'), 'prefix');
		$this->dir_chmod = Arr::get($settings->get('chmod'), 'dir');

		$this->recursively_update_dir($source, $destination);
		$this->cache->set('updates', $this->updates);
	}

	/**
	 * Recursively create missing directories and save unprocessed files
	 *
	 * @param  string  source directory
	 * @param  string  destination directory
	 */
	protected function recursively_update_dir($source, $destination)
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
			$this->recursively_update_dir("$source/$dir", "$destination/$dir");
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
	 * Update a file; relies on cache from update_dirs()
	 *
	 * @param   Config_Group  settings
	 * @return  array         updated files
	 */
	public function update_file(Config_Group $settings)
	{
		$file_chmod = Arr::get($settings->get('chmod'), 'file');

		// get list of files to update
		$updates = $this->cache->get('updates');
		$orig = key($updates);

		$file = array_shift($updates);
		$this->cache->set('updates', $updates);

		if (! $file) {
			// no more file, finish!
			$this->cache->delete('updates');
			$this->cache->delete('update_underway');
			return array();
		}

		// create image from original
		$img = Image::factory($orig);
		$img->upscale = Arr::get($settings->get('image'), 'upscale');
		foreach (array('image', 'thumb') as $type) {
			if (array_key_exists($type, $file)) {
				$conf = $settings[$type];

				// resize using current method
				$img->$conf['method']($conf['size'],
					Arr::get($conf, 'gap', null));

				// save file and set appropriate access rights
				$img->save($file[$type], $conf['quality']);
				chmod($file[$type], $file_chmod);
			}
		}

		return array_map(array('Debug', 'path'), $file);
	}
}
