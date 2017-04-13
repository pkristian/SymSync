<?php


class BFinder
{

	private const PATTERN_DOUBLE_DOT_END = <<<'EOL'
%[\\/]\.\.$%
EOL;

	private const PATTERN_SINGLE_DOT_END = <<<'EOL'
%[\\/]\.$%
EOL;


	private $directory_master;

	private $directory_slave;

	private $patterns;

	private $items = [];

	private $rawCount;


	/**
	 * BFinder constructor.
	 *
	 * @param string $directory_master
	 * @param $directory_slave
	 * @param array $patterns
	 *
	 * @throws \Exception
	 */
	public function __construct(string $directory_master, $directory_slave, array $patterns)
	{
		if (!is_dir($directory_master)) throw new Exception("Directory \"$directory_master\" does not exists");
		if (!is_dir($directory_slave)) throw new Exception("Directory \"$directory_slave\" does not exists");

		$this->directory_master = $directory_master;
		$this->directory_slave = $directory_slave;
		$this->patterns = $patterns;
	}


	/**
	 * @return BItem[]
	 */
	public function getItems()
	{
		if (!$this->items)
		{

			BAction::line('Loading master directory...');
			$this->find($this->directory_master);
			BAction::line('Loading slave directory...');
			$this->find($this->directory_slave);

			ksort($this->items,SORT_FLAG_CASE | SORT_NATURAL);
		}

		return $this->items;
	}


	private function find($directory)
	{
		$files = BAction::findInPath($directory);

		BAction::output("\t" . number_format(count($files)) . "");

		foreach ($files as $file)
		{
			$file = str_replace('/', '\\', $file); //backslashs
			$file = str_replace($directory, '', $file); //remove path

			if (preg_match(self::PATTERN_DOUBLE_DOT_END, $file)) //exclude doubledot
			{
				continue;
			}

			$file = preg_replace(self::PATTERN_SINGLE_DOT_END, '', $file); //dir

			foreach (PATTERNS as $PATTERN)
			{
				if (preg_match($PATTERN, $file))
				{
					$this->items[$file] = new BItem($file, $this->directory_master, $this->directory_slave);
					continue 2;
				}
			}
		}


	}


}
