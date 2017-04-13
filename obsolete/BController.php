<?php


class BController
{

	private const ACTIONS = [
		'l' => 'create symlink at slave, override slave',
		'm' => 'copy slave to master and create symlink at slave',
		's' => 'skip item',
		'x' => 'exit program',
	];


	/**
	 * @var BItem
	 */
	private $item;


	/**
	 * BController constructor.
	 *
	 * @param \BItem $item
	 */
	public function __construct(\BItem $item)
	{
		$this->item = $item;
	}


	public function resolve()
	{
		if ($this->item->pointedAtMaster()) return;
		//		echo chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J';
		echo "\n\nSlave:  ";
		echo $this->item->isLink(false) ? 'link' : '----';
		echo " ";
		echo $this->item->isFile(false) ? 'file' : '----';
		echo " ";
		echo $this->item->isDir(false) ? 'dir' : '---';
		echo " ";
		echo $this->item->getPath(false);

		echo "\nMaster: ";
		echo $this->item->isLink(true) ? 'link' : '----';
		echo " ";
		echo $this->item->isFile(true) ? 'file' : '----';
		echo " ";
		echo $this->item->isDir(true) ? 'dir' : '---';
		echo " ";
		echo $this->item->getPath(true);

		$action = $this->pickAction();
		$this->performAction($action);
	}


	private function performAction($action)
	{
		switch ($action)
		{
			case 'l':
			{
				BAction::removeSlave($this->item);
				BAction::createDirSlave($this->item);
				BAction::makeSymlink($this->item);
				break;
			}
			case 'm':
			{
				echo "\nCopy slave to master and replace by symlink";
				echo "\n>> Remove master: ";
				BAction::removeMaster($this->item);
				echo "\n>> copySlaveToMaster: ";
				BAction::copySlaveToMaster($this->item);
				echo "\n>> removeSlave: ";
				BAction::removeSlave($this->item);
				echo "\n>> makeSymlink: ";
				BAction::makeSymlink($this->item);
				break;
			}
			case 's':
			{
				echo "Skipping.";
				break;
			}
			case 'x':
			{
				die("Aborting.");
				break;
			}
			default:
			{
				die(
				"\n UNDEFINED ACTION \"$action\""
				);
			}
		}
	}


	/**
	 * @return string
	 */
	private function pickAction()
	{
		foreach (self::ACTIONS as $key => $value)
		{
			echo "\n\t$key = $value";
		}

		do
		{
			echo "\nWhat do do? ";
			$handle = fopen("php://stdin", "r");
			$line = fgets($handle);
			$response = trim($line);
			fclose($handle);
		} while (!key_exists($response, self::ACTIONS));

		return $response;
	}


}
