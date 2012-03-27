<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Static Media Controller
 *
 * @package  Gallery
 * @category Controller
 * @author   Kohana Team (Userguide_Controller)
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class Controller_Media extends Controller
{
	/**
	 * Display static media files
	 */
	public function action_index()
	{
		$path = $this->request->param('file');

		if ($file = Kohana::find_file('media', $path, '')) {
			// send file content as the response
			$this->auto_render = false;
			$this->response->body(file_get_contents($file));

			// set headers to allow caching
			$content_type = File::mime_by_ext(pathinfo($path, PATHINFO_EXTENSION));
			$this->response->headers('content-type', $content_type);
			$this->response->headers('last-modified', date('r', filemtime($file)));
		} else {
			$this->response->status(404);
		}
	}
}
