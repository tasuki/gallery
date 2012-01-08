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

		$updater = new Model_Updater();
		$updater->update_dirs($settings,
			DOCROOT . $dir['upload'],
			DOCROOT . $dir['gallery']);

		// TODO pass key to javascript to call action_update_file
		$this->template->body = $updater->key;
	}

	public function action_update_file()
	{
		$updater = new Model_Updater($this->request->param('key'));
		$updater->update_file();
	}
}
