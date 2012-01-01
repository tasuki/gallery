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
		$thumb = Kohana::$config->load('settings.thumbnail.prefix');
		$view->images = array();
		foreach ($directory->get_files() as $file) {
			$view->images[] = array(
				'link'  => "$gallery_dir/$dir/$file",
				'url'   => "$gallery_dir/$dir/$thumb$file",
				'title' => self::displayify($file),
			);
		}

		// get breadcrumbs
		$view->crumbs = self::get_crumbs($dir);

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
}
