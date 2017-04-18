<?php


namespace pkristian\SymSync;


class Verbose
{

	public static function banner()
	{
		echo "SymSync";
	}


	public static function error($string)
	{
		self::line("ERROR: ");
		self::output($string);
		exit;
	}


	public static function clear()
	{
		self::line('--- screen clear ---');
		self::line(chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J');
	}


	public static function br()
	{
		self::line('');
	}


	public static function line($string)
	{
		self::output("\n");
		self::output($string);
	}


	public static function output($string)
	{
		echo $string;
	}


	public static function ask($question, array $options)
	{
		$options = array_map('strtolower', $options);
		do
		{
			self::line($question . ' ');
			$line = self::getUserInput();
			$response = strtolower($line);
		} while (!in_array($response, $options));

		return $response;
	}


	private static function getUserInput()
	{
		$handle = fopen("php://stdin", "r");
		$input = fgets($handle);
		fclose($handle);

		return trim($input);
	}
}
