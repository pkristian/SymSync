<?php


namespace pkristian\SymSync\Action;


class SkipAction extends BaseAction
{

	public $description = 'skip';


	public function perform(\pkristian\SymSync\Item $item): void
	{
		return;
	}


	public function isPossible(\pkristian\SymSync\Item $item): bool
	{
		return true;
	}
}
