<?php


namespace pkristian\SymSync\Action;


class CopySlaveToMasterAction extends BaseAction
{

	public const ACTION = 'cs';

	public const DESCRIPTION = 'copy slave to master';


	public function perform(\pkristian\SymSync\Item $item): void
	{
		$this->delete($item, true);
		$this->copy($item, false);
	}


	public function isPossible(\pkristian\SymSync\Item $item): bool
	{
		if ($item->isPresent(false)  and !$item->isLink(false))
		{
			return true;
		}

		return false;
	}
}
