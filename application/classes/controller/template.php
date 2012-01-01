<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Shared controller functions
 */
class Controller_Template extends Kohana_Controller_Template
{
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
