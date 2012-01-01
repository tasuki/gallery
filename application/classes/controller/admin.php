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
		$this->template->body = "hi there";
	}
}
