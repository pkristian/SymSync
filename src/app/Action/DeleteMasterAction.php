<?php


namespace pkristian\SymSync\Action;


class DeleteMasterAction extends BaseAction
{

	public const ACTION = 'dm';

	public const DESCRIPTION = 'delete master';


	public function perform(\pkristian\SymSync\Item $item): void
	{
		$this->delete($item, true);
	}


	public function isPossible(\pkristian\SymSync\Item $item): bool
	{
		if ($item->isPresent(true))
		{
			return true;
		}

		return false;
	}
}
