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
	public function action_display()
	{
		$path = $this->request->param('file');
		$ext  = pathinfo($path, PATHINFO_EXTENSION);
		// remove extension from filename
		$base = substr($path, 0, -(strlen($ext) + 1));

		if ($file = Kohana::find_file('media', $base, $ext)) {
			// send file content as the response
			$this->auto_render = false;
			$this->response->body(file_get_contents($file));

			// set headers to allow caching
			$this->response->headers('content-type',  File::mime_by_ext($ext));
			$this->response->headers('last-modified', date('r', filemtime($file)));
		} else {
			$this->response->status(404);
		}
	}
}
