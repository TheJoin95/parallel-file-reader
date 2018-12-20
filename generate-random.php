<?php

$filename = dirname(__FILE__).'/data.txt';

file_put_contents($filename, "");

do{
	$text = "";
	for($i=0; $i < pow(10, 6); $i++) {
		$text .= mt_rand(0, pow(10, 6))."\n";
	}

	file_put_contents($filename, $text, FILE_APPEND);
}while(filesize($filename) < 1024*1024*1024*20); // filesize is in byte, I wanna randomize until 20GB

?>