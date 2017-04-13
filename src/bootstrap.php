<?php


include __DIR__ . "/../vendor/autoload.php";

include __DIR__."/Config.php";
include __DIR__."/Controller.php";
include __DIR__."/Finder.php";


$controller = new \SymSync\Controller(@$argv[1]);
$controller->run();
