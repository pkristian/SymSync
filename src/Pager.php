<?php


namespace pkristian\SymSync;


use Nette\Utils\Strings;

class Pager
{

	private const PAD_LIST = 24;
	private const PAD_SECTION = 8;


	public function banner(Config $config): bool
	{
		Verbose::clear();
		Verbose::line('SymSync by pkristian');
		Verbose::br();

		$this->showConfig($config);
		$response = Verbose::ask("Do you want to do anything? (Y/n)", ['', 'y', 'n']);
		if ($response == 'n') return false;

		return true;
	}


	/**
	 * @param \pkristian\SymSync\Item[] $items
	 */
	public function showItems(array $items): bool
	{
		Verbose::clear();
		Verbose::line("Found " . count($items) . " items:");
		Verbose::line(
			Strings::padRight('No.', self::PAD_SECTION)
			. Strings::padRight('master', self::PAD_SECTION)
			. Strings::padRight('linked', self::PAD_SECTION)
			. Strings::padRight('slave', self::PAD_SECTION)
			. Strings::padRight('name', self::PAD_SECTION)
		);


		foreach ($items as $key => $item)
		{
			Verbose::line(Strings::padRight($key + 1, self::PAD_SECTION));
			// master
			Verbose::output(
				Strings::padRight(
					($item->isDir(true) ? 'D' : '-')
					. ' '
					. ($item->isFile(true) ? 'F' : '-')
					. ' '
					. ($item->isLink(true) ? 'L' : '-')
					,
					self::PAD_SECTION
				)
			);
			// linked
			Verbose::output(
				Strings::padRight(
					($item->pointedAtMaster() ? '<====>' : '')

					,
					self::PAD_SECTION
				)
			);
			// slave
			Verbose::output(
				Strings::padRight(
					($item->isDir(false) ? 'd' : '-')
					. ' '
					. ($item->isFile(false) ? 'f' : '-')
					. ' '
					. ($item->isLink(false) ? 'l' : '-')
					,
					self::PAD_SECTION
				)
			);

			//name
			Verbose::output($item->getPath());
		}
		Verbose::br();

		$response = Verbose::ask("Foreach items? (Y/n)", ['', 'y', 'n']);
		if ($response == 'n') return false;

		return true;

	}


	/**
	 * @param \pkristian\SymSync\Item $item
	 * @param int $number
	 * @param int $count
	 * @param \pkristian\SymSync\Action\BaseAction[] $actions
	 *
	 * @return Action\BaseAction
	 */
	public function itemDetail(Item $item, int $number, int $count, array $actions)
	{
		Verbose::clear();
		Verbose::line("Item $number from $count:");
		Verbose::br();
		Verbose::line(Strings::padRight("Name:", self::PAD_LIST));
		Verbose::output($item->getPath());
		Verbose::br();
		Verbose::line(Strings::padRight("Property in MASTER:", self::PAD_LIST));
		Verbose::output(Strings::padRight(($item->isDir(true) ? 'DIR' : ''), self::PAD_SECTION));
		Verbose::output(Strings::padRight(($item->isFile(true) ? 'FILE' : ''), self::PAD_SECTION));
		Verbose::output(Strings::padRight(($item->isLink(true) ? 'LINK' : ''), self::PAD_SECTION));
		Verbose::line(Strings::padRight("Property in slave:", self::PAD_LIST));
		Verbose::output(Strings::padRight(($item->isDir(false) ? 'dir' : ''), self::PAD_SECTION));
		Verbose::output(Strings::padRight(($item->isFile(false) ? 'file' : ''), self::PAD_SECTION));
		Verbose::output(Strings::padRight(($item->isLink(false) ? 'link' : ''), self::PAD_SECTION));
		/* actions */
		Verbose::line("Actions:");
		foreach ($actions as $actKey => $action)
		{
			$isPossilbe = $action->isPossible($item);
			if (!$isPossilbe) unset($actions[$actKey]);
			Verbose::line(Strings::padRight("  " . $actKey, self::PAD_SECTION));
			Verbose::output($action->description);
		}
		$actionKeys = array_keys($actions);


		$response = Verbose::ask('What to do? (' . implode('/', $actionKeys) . ')', array_merge([''], $actionKeys));
		if (in_array($response, ['', 's'])) $response = 'S';

		return $actions[$response];
	}


	/* privates */


	private function showConfig(Config $config)
	{
		Verbose::line(Strings::padRight("Config file:", self::PAD_LIST) . $config->configFile);
		Verbose::line(Strings::padRight("Master dir: ", self::PAD_LIST) . $config->master);
		Verbose::line(Strings::padRight("Slave dir: ", self::PAD_LIST) . $config->slave);
		Verbose::line(Strings::padRight("Depth of search:", self::PAD_LIST) . $config->depth);
		$i = 0;
		do
		{
			$pattern = @$config->pattern[$i];
			if ($i == 0)
			{
				Verbose::line(
					Strings::padRight("Pattern:", self::PAD_LIST)
					. $pattern
				);
			}
			else
			{
				Verbose::line(Strings::padLeft('', self::PAD_LIST) . $pattern);
			}
			$i++;
		} while ($pattern);

		$i = 0;
		do
		{
			$ignore = @$config->ignore[$i];
			if ($i == 0)
			{
				Verbose::line(
					Strings::padRight("Ignore:", self::PAD_LIST)
					. $ignore
				);
			}
			else
			{
				Verbose::line(Strings::padLeft('', self::PAD_LIST) . $ignore);
			}
			$i++;
		} while ($ignore);
	}
}
