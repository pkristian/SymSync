<?php

namespace pkristian\SymSync;


class Controller
{

	/**
	 * @var Config
	 */
	public $config;

	/**
	 * @var \pkristian\SymSync\Item[]
	 */
	public $items = [];

	/**
	 * @var \pkristian\SymSync\Pager
	 */
	private $pager;


	public function __construct(string $configFile = null)
	{
		$this->pager = new Pager();

		if (!is_string($configFile))
		{
			Verbose::error('Config file not specified');
		}
		if (!file_exists($configFile))
		{
			Verbose::error("Config file you specified does not exists");
		}

		$this->config = new Config($configFile);
	}


	public function run(): void
	{
		$doStuff = $this->pager->banner($this->config);
		if (!$doStuff) $this->exit();
		$this->loadItems();
		$foreachItems = $this->pager->showItems($this->items);
		if (!$foreachItems) $this->exit();

		foreach ($this->items as $offset => $item)
		{
			$action = $this->pager->itemDetail(
				$item
				,
				$offset + 1
				,
				count($this->items)
				,
				$this->loadActions()
			);
			$action->perform($item);
		}


		$this->exit();
	}


	/* privates */

	private function loadItems()
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

		$items = array_merge($masterItems, $slaveItems);
		$items = array_unique($items);
		sort($items);

		foreach ($items as $item)
		{
			$this->items[] = new Item($item, $this->config);
		}
	}


	/**
	 * @return \pkristian\SymSync\Action\BaseAction[]
	 */
	private function loadActions(): array
	{
		return [
			'S' => new Action\SkipAction(),
			'e' => new Action\ExitAction(),
		];
	}


	private function exit()
	{
		Verbose::line('...goodbye');
		exit;
	}

}
