<?php

namespace SymSync;

class Config
{

	private const PARAM_MASTER = 'master';
	private const PARAM_SLAVE = 'slave';

	private const PARAM_DEPTH = 'depth';


	private const PARAM_PATTERN = 'pattern';
	private const PARAM_IGNORE = 'ignore';


	public $configFile;

	/* directories */
	public $master;

	public $slave;

	/* settings */
	public $depth;

	/* patterns */
	public $pattern = [];

	public $ignore = [];


	/**
	 * Config constructor.
	 *
	 * @param string $configFile
	 */
	public function __construct(string $configFile)
	{
		$this->configFile = $configFile;

		$this->load();
		$this->check();
	}


	private function load()
	{
		$ini = parse_ini_file($this->configFile);

		$this->master = @$ini[self::PARAM_MASTER];
		$this->slave = @$ini[self::PARAM_SLAVE];

		$this->depth = @$ini[self::PARAM_DEPTH];

		$this->pattern = (array) @$ini[self::PARAM_PATTERN];
		$this->ignore = (array) @$ini[self::PARAM_IGNORE];
	}


	private function check()
	{
		if (!$this->master)
		{
			throw new \Exception("Master directory is not specified.");
		}
		if (!$this->slave)
		{
			throw new \Exception("Slave directory is not specified.");
		}


	}


}
