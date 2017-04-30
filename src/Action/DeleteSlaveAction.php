<?php


namespace pkristian\SymSync\Action;


class DeleteSlaveAction extends BaseAction
{

	public const ACTION = 'ds';

	public const DESCRIPTION = 'delete slave';


	public function perform(\pkristian\SymSync\Item $item): void
	{
		$this->delete($item, false);
	}


	public function isPossible(\pkristian\SymSync\Item $item): bool
	{
		if ($item->isPresent(false))
		{
			return true;
		}

		return false;
	}
}
