<?php


namespace pkristian\SymSync\Action;


abstract class BaseAction
{

	public $description;


	public abstract function perform(\pkristian\SymSync\Item $item): void;


	public abstract function isPossible(\pkristian\SymSync\Item $item): bool;

}
