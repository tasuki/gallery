<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Directory iterator that doesn't complain when dir is missing
 *
 * @package  Gallery
 * @author   Vit 'tasuki' Brunner
 * @license  GPL
 */
class RelaxedDirectoryIterator extends DirectoryIterator
{
	protected $fake;

	/**
	 * If directory doesn't exist, pretend it does
	 *
	 * @param  string  path
	 */
	public function __construct($path)
	{
		try {
			parent::__construct($path);
		} catch (UnexpectedValueException $e) {
			$this->fake = $path;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPath()
	{
		if ($this->fake) {
			return $this->fake;
		} else {
			return parent::getPath();
		}
	}
}
