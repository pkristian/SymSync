<?php


namespace pkristian\SymSync\Action;


abstract class BaseAction
{

	public const DEFAULT_ACTION = 'S';

	public const ACTION = null;

	public const DESCRIPTION = null;



	public abstract function perform(\pkristian\SymSync\Item $item): void;


	public abstract function isPossible(\pkristian\SymSync\Item $item): bool;


	protected function delete(\pkristian\SymSync\Item $item, bool $master)
	{
		\pkristian\SymSync\Verbose::line("Deleting: " . $item->getPath($master));
		\Nette\Utils\FileSystem::delete($item->getPath($master));
		\pkristian\SymSync\Verbose::output(" [ok]");
	}


	protected function copy(\pkristian\SymSync\Item $item, bool $masterToSlave)
	{
		$source = $masterToSlave;
		$destination = !$masterToSlave;

		\pkristian\SymSync\Verbose::line(
			"Copying form: "
			. $item->getPath($source)
			. " to "
			. $item->getPath($destination)
		);
		\Nette\Utils\FileSystem::copy($item->getPath($source), $item->getPath($destination));
		\pkristian\SymSync\Verbose::output(" [ok]");
	}


	protected function link(\pkristian\SymSync\Item $item, bool $fromMaster)
	{
		$source = $fromMaster;
		$destination = !$fromMaster;

		\pkristian\SymSync\Verbose::line("Creating symlink...");

		symlink($item->getPath($source), $item->getPath($destination));

		\pkristian\SymSync\Verbose::output(" [ok]");
	}



}
