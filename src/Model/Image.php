<?php

namespace App\Model;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Metadata\ExifMetadataReader;

class Image
{
	const FILE_CHMOD = 0664;

	private $imagine;

	public function __construct()
	{
		$this->imagine = new Imagine();
	}

	public function generate($orig, $dst)
	{
		// create image from original
		$img = $this->imagine
			->setMetadataReader(new ExifMetadataReader())
			->open($orig);

		if (array_key_exists('image', $dst)) {
			$this->resizeTo($img, $dst['image'], 3072, 3072);
		}

		if (array_key_exists('thumb', $dst)) {
			$this->resizeTo($img, $dst['thumb'], 600, 300);
		}

		return $dst;
	}

	private function resizeTo($img, $dst, $width, $height)
	{
		$metadata = $img->metadata();
		$iwidth = $metadata['computed.Width'];
		$iheight = $metadata['computed.Height'];
		$saveOptions = [ 'jpeg_quality' => 85, 'webp_quality' => 85 ];

		$ratio = $iwidth / $iheight;

		if ($width / $height > $ratio) {
			$width = $height * $ratio;
		} else {
			$height = $width / $ratio;
		}

		if ($width > $iwidth || $height > $iheight) {
			$img->save($dst, $saveOptions);
		} else {
			$img->resize(new Box($width, $height))
				->save($dst, $saveOptions);
		}

		chmod($dst, self::FILE_CHMOD);
	}
}
