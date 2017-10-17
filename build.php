<?php

const PHAR_FILE = "SymSync.phar";
unlink(PHAR_FILE);
$phar = new Phar(
	PHAR_FILE
	,
	FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME
	,
	PHAR_FILE
);

$phar->buildFromDirectory("src");
$phar->setStub($phar->createDefaultStub("index.php"));
