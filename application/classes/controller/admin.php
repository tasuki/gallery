<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Admin controller
 *
 * @package  Gallery
 * @category Controller
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Controller_Admin extends Controller_Template
{
	/**
	 * Set title
	 */
	public function before()
	{
		parent::before();
		$this->set_title('admin');
	}

	public function action_update()
	{
		$dir = Kohana::$config->load('application.dir');
		$settings = Kohana::$config->load('settings');

		$updater = new Model_Updater($settings);
		$updater->update_dir(
			DOCROOT . $dir['upload'],
			DOCROOT . $dir['gallery']);

		// TODO cache $updater->updates and update one per request

		$this->template->body = "hi there";
	}
}
