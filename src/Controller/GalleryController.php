<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GalleryController extends AbstractController
{
	public function index(string $dir): Response
	{
		return new Response(
			"<html><body>Show dir: '$dir'</body></html>"
		);
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
	protected static function get_calibration($min = 0, $max = 255, $items = 30)
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
