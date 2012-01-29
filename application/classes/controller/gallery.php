<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Gallery controller
 *
 * @package  Gallery
 * @category Controller
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Controller_Gallery extends Controller_Template
{
	/**
	 * Load styles and configs according to template
	 */
	public function before()
	{
		parent::before();

		$config = Kohana::$config->load('settings');
		$this->template->styles  = $config['styles'];
		$this->template->scripts = $config['scripts'];
	}

	/**
	 * Show gallery folder
	 */
	public function action_index()
	{
		$view = View::factory('gallery');
		$dir = $this->request->param('dir');
		$this->set_title($dir);

		// load directory model
		$gallery_dir = Kohana::$config->load('application.dir.gallery');
		$directory = new Model_Directory(DOCROOT . "$gallery_dir/$dir");

		// get sub-galleries
		$view->galleries = array();
		foreach ($directory->get_dirs() as $subdir) {
			$view->galleries["$dir/$subdir"] = self::displayify($subdir);
		}

		// get images
		$prefix = Kohana::$config->load('settings.thumb.prefix');
		$view->images = array();

		foreach ($directory->get_files(array($prefix)) as $file) {
			$view->images[] = array(
				'link'  => "$gallery_dir/$dir/$file",
				'url'   => "$gallery_dir/$dir/$prefix$file",
				'title' => self::displayify($file),
				'file'  => $file,
			);
		}

		// get breadcrumbs and calibration
		$view->crumbs = self::get_crumbs($dir);
		$view->calibration = self::get_calibration();

		$this->template->body = $view;
	}

	/**
	 * Assemble breadcrumbs
	 *
	 * @param   string  url to get the crumbs from
	 * @return  array   link => title
	 */
	protected static function get_crumbs($dir)
	{
		// set home crumb
		$url = '/';
		$crumbs = array($url => Kohana::message('global', 'title'));

		// set crumbs based on directories
		foreach (array_filter(explode('/', $dir)) as $title) {
			$url .= '/' . $title;
			$crumbs[$url] = self::displayify($title);
		}

		// unset link of last crumb
		$crumbs[''] = array_pop($crumbs);

		return $crumbs;
	}

	/**
	 * Get steps for calibration
	 */
	protected static function get_calibration($min = 0, $max = 255, $items = 24)
	{
		$colors = array();
		$step = ($max - $min) / $items;

		for ($i = $min; $i <= $max; $i += $step) {
			$x = dechex($i);
			if (strlen($x) == 1)
				$x = "0$x";

			$colors[] = "#$x$x$x";
		}

		return $colors;
	}
}
