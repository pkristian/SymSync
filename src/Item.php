<?php
namespace pkristian\SymSync;

class Item
{

	public $name;

	/**
	 * @var \pkristian\SymSync\Config
	 */
	private $config;


	/**
	 * BItem constructor.
	 *
	 * @param $name
	 * @param \pkristian\SymSync\Config $config
	 *
	 * @internal param $dirMaster
	 * @internal param $dirSlave
	 */
	public function __construct($name, Config $config)
	{
		$this->name = $name;
		$this->config = $config;
	}


	public function isLink($master)
	{
		return is_link($this->getPath($master));
	}


	public function isFile($master)
	{
		return is_file($this->getPath($master));
	}


	public function isDir($master)
	{
		return is_dir($this->getPath($master));
	}


	public function isPresent($master)
	{
		if (
			$this->isLink($master)
			or
			$this->isFile($master)
			or
			$this->isDir($master)
		)
		{
			return true;
		}

		return false;
	}




	public function getPath($master = null)
	{
		if (is_null($master))
		{
			return $this->name;
		}

		return
			(
			$master
				? $this->config->master
				: $this->config->slave
			)
			.
			$this->name;
	}


	public function pointedAtMaster(): bool
	{

		return @readlink($this->getPath(false)) === @readlink($this->getPath(true));
	}


}


