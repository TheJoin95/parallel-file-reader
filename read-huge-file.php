<?php

define("BUFFER_SIZE", pow(2, 18));
ini_set("memory_limit", "512M");

$opt = getopt('n:p:', array('startByte:', 'endByte:'));
$file = dirname(__FILE__) . '/data.txt';

//$opt['p'] = isset($opt['p']) ? (int)$opt['p'] : 5;
$opt['n'] = isset($opt['n']) ? (int)$opt['n'] : 100;
$opt['startByte'] = isset($opt['startByte']) ? (int)$opt['startByte'] : 0;
$opt['endByte'] = isset($opt['endByte']) ? (int)$opt['endByte'] : filesize($file);

$fp = fopen($file, "r");
$largestNumbers = array();

if($opt['startByte'] != 0) {
	fseek($fp, $opt['startByte']);
}

$pieces = 0;
$byteRead = 0;

$minByteExit = $opt['endByte'] - $opt['startByte'];

while(($lines = fread($fp, BUFFER_SIZE)) && $byteRead < $minByteExit) {
	echo $pieces."\n";
	$byteRead += BUFFER_SIZE;
	$lines = explode("\n", $lines);
	arsort($lines, SORT_NUMERIC);
	$largestNumbers = array_merge($largestNumbers, array_slice($lines, 0, $opt['n']));
	unset($lines);

	arsort($largestNumbers, SORT_NUMERIC);
	$largestNumbers = array_slice($largestNumbers, 0, $opt['n']);
	// echo "lines: ".count($lines)."\n";
	$pieces++;
}

file_put_contents(dirname(__FILE__).'/results/result.'.uniqid().'.txt', json_encode($largestNumbers));
// echo "Total pieces: $pieces\n";
fclose($fp);

?>