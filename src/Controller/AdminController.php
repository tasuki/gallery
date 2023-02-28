<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Directory;
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
		$updater->update_dirs(
			$this->getParameter('kernel.project_dir') . '/upload/',
			$this->getParameter('kernel.project_dir') . '/public/gallery/',
		);

		// update view, with url to call to update files
		return $this->render("admin.twig", [
			"title" => "admin",
			"key" => $updater->key,
		]);
	}

	/**
	 * Update single file
	 */
	public function update_file($key)
	{
		$reload = true;
		try {
			$updater = new Updater($key);
			$results = $updater->update_file();

			if (count($results) === 0) {
				$reload = false;
			}
		} catch (Exception $e) {
			$results = array('fatal' => $e->getMessage());
			$reload = false;
		}

		return $this->json([
			'reload' => $reload,
			'results' => $results,
		]);
	}
}
