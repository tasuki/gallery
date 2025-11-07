<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Directory;
use App\Model\Helpers;

class GalleryController extends AbstractController
{
	public function index(Request $request, string $dir): Response
	{
		$dirs = explode('/', $dir);
		$defaults = [
			"title" => Helpers::title($dir) . $this->getParameter("title"),
			"crumbs" => $this->get_crumbs($dirs),
			"auth" => false,
			"error" => "",
			"galleries" => [],
			"images" => [],
			"neighbors" => [],
			"license_link" => $this->getParameter("license_link"),
			"license_name" => $this->getParameter("license_name"),
			"calibration" => self::get_calibration(),
		];

		try {
			$directory = new Directory($this->getParameter('gallery_dir') . "/$dir");
		} catch (\UnexpectedValueException $e) {
			$response = $this->render("gallery.twig", array_replace($defaults, [
				"title" => "404: Not Found",
				"error" => "404: This gallery could not be found!",
			]));
			$response->setStatusCode(404);
			return $response;
		}

		if ($directory->is_protected()) {
			$session = $request->getSession();
			$unlocked_dirs = $session->get('unlocked_dirs', []);
			if ($request->request->has('password')) {
				$submitted_pass = $request->request->get('password');
				$actual_pass = $directory->get_password();
				if ($submitted_pass === $actual_pass) {
					$unlocked_dirs[$dir] = true;
					$session->set('unlocked_dirs', $unlocked_dirs);
				}
			}

			if (! array_key_exists($dir, $unlocked_dirs)) {
				$response = $this->render("gallery.twig", array_replace($defaults, [
					"auth" => true,
					"title" => "401: Not Authenticated",
					"error" => "401: Please provide the password to access this gallery",
					"neighbors" => $this->get_neighbors($dir, $directory),
				]));
				$response->setStatusCode(401);
				return $response;
			}
		}

		return $this->render("gallery.twig", array_replace($defaults, [
			"galleries" => $this->get_galleries($dirs, $directory),
			"images" => $this->get_images($dir, $directory),
			"neighbors" => $this->get_neighbors($dir, $directory),
		]));
	}

	private function get_galleries($dirs, $directory)
	{
		$galleries = [];

		foreach ($directory->get_dirs() as $subdir) {
			$key = array_filter(array_merge($dirs, [$subdir]));
			$galleries["/" . join('/', $key)] = Helpers::displayify($subdir);
		}

		return $galleries;
	}

	private function get_images($dir, $directory)
	{
		$prefix = $this->getParameter("thumb_prefix");
		$images = [];

		foreach ($directory->get_files([$prefix]) as $file) {
			$thumb = Helpers::thumb($file);
			try {
				list($iwidth, $iheight) =
					getimagesize($this->getParameter('gallery_dir') . "/$dir/$thumb");

				$images[] = array(
					'link'   => "/gallery/$dir/$file",
					'src'    => "/gallery/$dir/$thumb",
					'title'  => Helpers::displayify($file),
					'file'   => $file,
					'width'  => $iwidth,
					'height' => $iheight,
				);
			} catch (\ErrorException $e) {
				// skip if no thumb
			}
		}

		return $images;
	}

	private function get_neighbors($dir, $directory)
	{
		$neighbors = [];

		if (!$dir) {
			return $neighbors;
		}

		$parent = dirname($dir);
		foreach ($directory->get_neighbors() as $rel => $name) {
			$neighbors[$rel] = [
				'link'  => "/$parent/$name",
				'title' => Helpers::displayify($name),
			];
		}

		return $neighbors;
	}

	/**
	 * Assemble breadcrumbs
	 *
	 * @param   array string  url to get the crumbs from
	 * @return  array   link => title
	 */
	private function get_crumbs($dirs)
	{
		// set home crumb
		$crumbs = ['/' => $this->getParameter("title")];

		// set crumbs based on directories
		$url = '';
		foreach (array_filter($dirs) as $title) {
			$url .= '/' . $title;
			$crumbs[$url] = Helpers::displayify($title);
		}

		// unset link of last crumb
		$crumbs[''] = array_pop($crumbs);

		return $crumbs;
	}

	/**
	 * Get steps for calibration
	 */
	private static function get_calibration($min = 0, $max = 255, $items = 30)
	{
		$colors = [];
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
