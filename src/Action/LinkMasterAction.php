<?php


namespace pkristian\SymSync\Action;


class LinkMasterAction extends BaseAction
{

	public const ACTION = 'lm';

	public const DESCRIPTION = 'link to master';


	public function perform(\pkristian\SymSync\Item $item): void
	{
		$this->delete($item, false);
		$this->link($item, true);
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
