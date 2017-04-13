<?php
include __DIR__ . '/Finder.php';
include __DIR__ . '/Item.php';
include __DIR__ . '/Controller.php';
include __DIR__ . '/Action.php';


const DIR_SLAVE = 'C:\xampp\htdocs\\';
const DIR_MASTER = 'C:\shared\htdocs\master\\';

const PATTERNS = [
	<<<'EOD'
/\.idea$/
EOD
	,
];


echo "\nSlave directory: \t" . DIR_SLAVE ;
echo "\nMaster directory: \t" . DIR_MASTER;
BAction::line();
$finder = new BFinder(DIR_MASTER, DIR_SLAVE, PATTERNS);
$items = $finder->getItems();
BAction::line();
$itemsCount = count($items);
BAction::line("Found $itemsCount valid items:");


BAction::line("No.\tslave\tmaster\tlinked\tname");
$i = 1;
foreach ($items as $item)
{
	BAction::line($i++ . "\t".$item->show());
}
BAction::line("Enter for continue.");
BAction::waitForResponse();

foreach ($items as $item)
{
	$controller = new BController($item);
	$controller->resolve();
}
exit;


