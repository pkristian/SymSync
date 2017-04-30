<?php


namespace pkristian\SymSync\Action;


class CopyMasterToSlaveAction extends BaseAction
{

	public const ACTION = 'cm';

	public const DESCRIPTION = 'copy MASTER to slave';


	public function perform(\pkristian\SymSync\Item $item): void
	{
		$this->delete($item, false);
		$this->copy($item, true);
	}


	public function isPossible(\pkristian\SymSync\Item $item): bool
	{
		if ($item->isPresent(true) and !$item->isLink(true))
		{
			return true;
		}

		return false;
	}
}
