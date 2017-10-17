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
			do
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


				try
				{
					$action->perform($item);
				}
				catch (\Exception $e)
				{
					Verbose::error($e->getMessage());
				}
				Verbose::ask("Press [enter] to continue...");
			} while ($action::ACTION !== $action::DEFAULT_ACTION);
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
			$this->config->slave
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
		$actions = [
			new Action\SkipAction(),
			new Action\ExitAction(),
			new Action\DeleteMasterAction(),
			new Action\DeleteSlaveAction(),
			new Action\CopyMasterToSlaveAction(),
			new Action\CopySlaveToMasterAction(),
			new Action\LinkMasterAction(),
		];
		$outputActions = [];

		/** @var \pkristian\SymSync\Action\BaseAction $action */
		foreach ($actions as $action)
		{
			$outputActions[$action::ACTION] = $action;
		}

		return $outputActions;


	}


	private function exit()
	{
		Verbose::line('...goodbye');
		exit;
	}

}
