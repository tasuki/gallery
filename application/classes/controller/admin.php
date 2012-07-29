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

		$this->template->styles = array(
			'media/css/style.css',
		);

		$this->template->scripts = array(
			'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js',
			array(
				'test'     => 'window.jQuery',
				'fallback' => 'media/js/jquery-1.7.1.min.js',
			),
			'media/js/admin.js',
		);
	}

	/**
	 * Start update of the whole gallery
	 */
	public function action_update()
	{
		$dir = Kohana::$config->load('application.dir');
		$settings = Kohana::$config->load('settings');

		// recursively update directories
		$updater = new Model_Updater();
		$updater->update_dirs($settings,
			DOCROOT . $dir['upload'],
			DOCROOT . $dir['gallery']);

		// update view, with url to call to update files
		$view = View::factory('admin/update');
		$view->fetch_url = Route::url('admin', array(
			'action' => 'update_file',
			'key'    => $updater->key,
		));

		$this->template->body = $view;
	}

	/**
	 * Update single file
	 *
	 * Relies on file list being cached from action_update call.
	 */
	public function action_update_file()
	{
		try {
			$updater  = new Model_Updater($this->request->param('key'));
			$settings = Kohana::$config->load('settings');
			$results  = $updater->update_file($settings);

			if (count($results)) {
				$status = 'ok';
			} else {
				$results = array('finished' => 'success');
				$status  = 'finished';
			}
		} catch (Kohana_Exception $e) {
			$results = array('error' => $e->getMessage());
			$status  = 'error';
		} catch (Exception $e) {
			$results = array('fatal' => $e->getMessage());
			$status  = 'finished';
		}

		$view = View::factory('admin/update_file')
			->set('results', $results)
			->render();

		$this->data = array(
			'status' => $status,
			'view'   => $view,
		);
	}
}
