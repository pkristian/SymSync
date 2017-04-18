<?php


namespace pkristian\SymSync\Action;


class ExitAction extends BaseAction
{

	public $description = 'exit';


	public function perform(\pkristian\SymSync\Item $item): void
	{
		exit;
	}


	public function isPossible(\pkristian\SymSync\Item $item): bool
	{
		return true;
	}
}
