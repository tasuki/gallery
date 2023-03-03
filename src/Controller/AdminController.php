<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Updater;

class AdminController extends AbstractController
{
	/**
	 * Start update of the whole gallery
	 */
	public function update(): Response
	{
		// recursively update directories
		$updater = new Updater();
		$files = $updater->update_dirs(
			$this->getParameter('upload_dir'),
			$this->getParameter('gallery_dir'),
		);

		// update view, with url to call to update files
		return $this->render("admin.twig", [
			"title" => "admin",
			"files" => count($files),
		]);
	}

	/**
	 * Update single file
	 */
	public function update_file()
	{
		$updater = new Updater();
		$reload = true;

		try {
			$results = $updater->update_file();

			if (count($results) === 0) {
				$reload = false;
			}
		} catch (Exception $e) {
			$results = array('error' => $e->getMessage());
			$reload = false;
		}

		return $this->json([
			'reload' => $reload,
			'results' => $results,
		]);
	}
}
