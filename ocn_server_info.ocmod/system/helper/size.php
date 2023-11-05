<?php

function du_dir(string $dir)
{
	$sizeString = shell_exec('du -sh ' . $dir);
	$sizeArray = preg_split('/\t|\r\n|\r|\n/', $sizeString);
	
	return $sizeArray[0];
}

function bytes_to_str(int $bytes)
{
	$prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
	$base = 1024;
	$class = min((int)log($bytes, $base), count($prefix) - 1);
	
	return sprintf('%1.2f', $bytes / pow($base, $class)) . $prefix[$class];
}
