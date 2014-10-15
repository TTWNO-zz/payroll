<?php
	$fileName = 'version.txt';

	$file = fopen($fileName, 'r');
	$VERSION = fread($file, filesize($fileName));
	fclose($file);
?>
