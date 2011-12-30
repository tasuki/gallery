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
	public function before()
	{
		parent::before();

		View::set_global('title', Kohana::message('global', 'title'));
	}

	public function action_display()
	{
		$url_parts = $this->request->param('url_parts');

		$dir = new Model_Directory($url_parts);

		$view = View::factory('gallery');
		$view->dirs   = $dir->get_dirs();
		$view->files  = $dir->get_files();
		$view->crumbs = self::get_crumbs($url_parts);

		$this->template->body = $view;
	}

	/**
	 * Assemble breadcrumbs
	 *
	 * @param   array  url parts
	 * @return  array  link => title
	 */
	protected static function get_crumbs(array $url_parts)
	{
		// trim last slash so we can add it again
		$url = rtrim(Url::base(), '/');

		// set home crumb
		$crumbs = array($url => Kohana::message('global', 'title'));

		// set crumbs based on directories
		foreach ($url_parts as $title) {
			$url .= '/' . $title;
			$crumbs[$url] = $title;
		}

		// unset link of last crumb
		$crumbs[''] = array_pop($crumbs);

		return $crumbs;
	}
}
