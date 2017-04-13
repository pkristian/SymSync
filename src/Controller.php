<?php

namespace SymSync;


class Controller
{

	/**
	 * @var Config
	 */
	public $config;

	public $items;


	public function __construct(string $configFile)
	{
		$this->config = new Config($configFile);
	}


	public function run(): void
	{
		$masterFinder = new Finder(
			$this->config->master
			, $this->config->pattern
			, $this->config->ignore
			, $this->config->depth
		);
		$masterItems = $masterFinder->findNames();

		$slaveFinder = new Finder(
			$this->config->master
			, $this->config->pattern
			, $this->config->ignore
			, $this->config->depth
		);
		$slaveItems = $slaveFinder->findNames();

		$this->items = array_merge($masterItems, $slaveItems);
		$this->items = array_unique($this->items);
		sort($this->items);

		$this->items = $this->items;

	}


	/* privates */


}
