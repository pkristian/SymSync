<?php

namespace pkristian\SymSync;

use Nette\Utils\Strings;

class Finder
{


	private $directory;


	private $pattern;

	private $ignore;

	private $depth = -1;

	private $names = [];

	private $rawCount;


	/**
	 * BFinder constructor.
	 *
	 * @param string $directory
	 * @param array $pattern
	 * @param array $ignore
	 * @param int $depth
	 *
	 * @internal param string $directory_master
	 * @internal param $directory_slave
	 * @internal param array $patterns
	 *
	 */
	public function __construct(string $directory, array $pattern, array $ignore, int $depth = -1)
	{
		$this->directory = $directory;
		$this->pattern = $pattern;
		$this->ignore = $ignore;
		$this->depth = $depth;
	}


	public function findNames()
	{
		$this->findInDirectory();

		return $this->names;
	}


	private function findInDirectory()
	{
		$finder = \Nette\Utils\Finder::find('*')
			->from($this->directory)
			->limitDepth($this->depth)
		;
		foreach (
			$finder as $name
		)
		{
			$this->rawCount++;
			$processedName = $this->processName((string) $name);
			if ($processedName)
			{
				$this->names[] = $processedName;
			}
		}

	}


	private function processName(string $name):? string
	{
		/* remove base path*/
		if (!Strings::startsWith($name, $this->directory)) //check if directory is correct
		{
			throw new \Exception("Name '$name' should start with '$this->directory");
		}
		$name = substr($name, strlen($this->directory));

		/* pattern */
		foreach ($this->pattern as $pattern)
		{
			if (preg_match($pattern, $name))
			{
				/* ignore */
				foreach ($this->ignore as $ignore)
				{
					if (preg_match($ignore, $name))
					{
						return null;
					}
				}

				return $name;
			}
		}

		return null;
	}


}
