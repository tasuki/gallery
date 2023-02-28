<?php

namespace App\Controller;

class Helpers {
	/**
	 * Get title from url
	 */
	public static function title(string $url): string
	{
		return self::displayify($url) . " – ";
	}

	/**
	 * Remove three letter suffix, replace undescores and dashes with spaces
	 */
	public static function displayify($text)
	{
		return preg_replace('/\....$/', '', preg_replace('/[_-]/', ' ', $text));
	}
}
