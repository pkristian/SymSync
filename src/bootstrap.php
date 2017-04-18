<?php


include __DIR__ . "/../vendor/autoload.php";

include __DIR__."/Config.php";
include __DIR__."/Controller.php";
include __DIR__."/Finder.php";
include __DIR__."/Item.php";
include __DIR__."/Pager.php";
include __DIR__."/Verbose.php";

include __DIR__."/Action/BaseAction.php";
include __DIR__."/Action/SkipAction.php";
include __DIR__."/Action/ExitAction.php";


$controller = new \pkristian\SymSync\Controller(@$argv[1]);
$controller->run();
