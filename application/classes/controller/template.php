<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Shared controller functions
 */
class Controller_Template extends Kohana_Controller_Template
{
	protected $data;

	/**
	 * Take care of ajax requests
	 */
	public function after()
	{
		if ($this->request->is_ajax()) {
			// use json_encoded data as the response
			$this->response->body(json_encode($this->data));
		} else {
			parent::after();
		}
	}

	/**
	 * Set title to template
	 *
	 * @param  string  url
	 */
	protected function set_title($url)
	{
		$title = '';
		if ($url) {
			$exploded = explode('/', $url);
			$title = self::displayify(array_pop($exploded)) . " &ndash; ";
		}

		$this->template->title = $title . Kohana::message('global', 'title');
	}

	/**
	 * Remove undescores and dashes from text, replace with spaces
	 *
	 * @param   string  original text
	 * @return  string  text for displaying
	 */
	protected static function displayify($text)
	{
		return preg_replace('/[_-]/', ' ', $text);
	}
}
