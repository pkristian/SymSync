<?php


class BItem
{

	private $name;

	private $dirMaster;

	private $dirSlave;


	/**
	 * BItem constructor.
	 *
	 * @param $name
	 * @param $dirMaster
	 * @param $dirSlave
	 */
	public function __construct($name, $dirMaster, $dirSlave)
	{
		$this->name = $name;
		$this->dirMaster = $dirMaster;
		$this->dirSlave = $dirSlave;
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


	public function getPath($master = null)
	{
		if (is_null($master))
		{
			return $this->name;
		}

		return
			(
			$master
				? $this->dirMaster
				: $this->dirSlave
			)
			.
			$this->name;
	}


	public function pointedAtMaster(): bool
	{

		return @readlink($this->getPath(false)) === @readlink($this->getPath(true));
	}


	public function show()
	{
		$return = '';
		$return .= $this->isLink(false) ? 'l' : '-';
		$return .= $this->isFile(false) ? 'f' : '-';
		$return .= $this->isDir(false) ? 'd' : '-';
		$return .= "\t";
		$return .= $this->pointedAtMaster() ? " ~ " : "   ";
		$return .= "\t";
		$return .= $this->isLink(true) ? 'L' : '-';
		$return .= $this->isFile(true) ? 'F' : '-';
		$return .= $this->isDir(true) ? 'D' : '-';
		$return .= "\t";
		$return .= $this->getPath();

		return $return;

	}


}


