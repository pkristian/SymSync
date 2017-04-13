<?php


/**
 * Created by PhpStorm.
 * User: pkristian
 * Date: 04.04.2017
 * Time: 15:44
 */
class BAction
{

	public static function removeMaster(BItem $item)
	{
		self::remove($item, true);
	}


	public static function removeSlave(BItem $item)
	{
		self::remove($item, false);
	}


	public static function createDirMaster(BItem $item)
	{
		self::createDir($item, true);
	}


	public static function createDirSlave(BItem $item)
	{
		self::createDir($item, false);
	}


	public static function makeSymlink(BItem $item)
	{
		$command = 'mklink '
			. ($item->isDir(true) ? " /D " : null)
			. "\"" . $item->getPath(false)
			. '" "'
			. $item->getPath(true)
			. '"';
		echo "\n$command\n";
		system($command);
	}


	public static function copySlaveToMaster(BItem $item)
	{
		if ($item->isFile(false)) /*file*/
		{
			copy($item->getPath(false), $item->getPath(true));
		}
		else /*folder*/
		{
			$command = 'robocopy '
				. ' "' . $item->getPath(false) . '" '
				. ' "' . $item->getPath(true) . '" '
				. " /E ";
			echo "\n$command\n";
			system($command);
		}


	}


	public static function findInPath($path): array
	{
		$string = self::runCommand(
			'@echo off & for /f "delims=*" %A in (\'dir /s /b "' . $path . '."\') do echo %~fA'
		);
		$files = explode("\n", $string);
		foreach ($files as &$file)
		{
			$file = trim($file);
			$file = str_ireplace($path, null, $file);
		}

		return $files;
	}


	public static function output($string)
	{
		echo $string;
	}


	public static function line($string = null)
	{
		echo "\n" . $string;
	}


	public function pause()
	{
		echo "\n";
		self::runCommand('pause');
	}


	public static function runCommand(string $command)
	{
		ob_start();
		{
			system($command);
		}

		return ob_get_clean();
	}


	public static function waitForResponse(array $availableResponses = [])
	{
		$handle = fopen("php://stdin", "r");
		$line = fgets($handle);
		$response = trim($line);
		fclose($handle);

		if (in_array($response, $availableResponses))
		{
			return $response;
		}

		return false;
	}


	/* privates */
	public static function remove(BItem $item, $master)
	{

		@system(
			($item->isFile($master) ? 'del' : 'rd')
			. ' /s /q "' . $item->getPath($master) . '"'
		);
	}


	private static function createDir(BItem $item, $master)
	{
		mkdir(dirname($item->getPath($master)), 0755, true);
	}

}
