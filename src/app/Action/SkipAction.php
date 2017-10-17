<?php


namespace pkristian\SymSync\Action;


class SkipAction extends BaseAction
{

	public const ACTION = 'S';

	public const DESCRIPTION = 'skip';


	public function perform(\pkristian\SymSync\Item $item): void
	{
		\pkristian\SymSync\Verbose::line("Going to next item.");
		return;
	}


	public function isPossible(\pkristian\SymSync\Item $item): bool
	{
		return true;
	}
}
