<?php

/**
*
* Launcher of python script in multiprocess enviroment
* This script will launch N process of a python script to read a file
* Then, at the end, it will process a dir with some files to print out the results
*
*/

// Get params from CLI
$opt = getopt('n:p:');
$readFileScript = dirname(__FILE__) . '/read-huge-file.php'; // Old test
$readFileScriptPython = dirname(__FILE__) . '/read.py'; // Python script
$file = dirname(__FILE__) . '/data.txt'; // File to read

define("SIMULTANEOUS_PROCESS", 8); // Max process running at the same time

$opt['p'] = isset($opt['p']) ? (int)$opt['p'] : 40; // Split in 40 part
$opt['n'] = isset($opt['n']) ? (int)$opt['n'] : 100; // Gen N

$startByte = 0;
$byteIncrement = (int)(filesize($file) / $opt['p']);
$endByte = $byteIncrement;

$filenamesRemove = glob(dirname(__FILE__).'/results/*'); // Free space from the results dir
if(!empty($filenamesRemove)) {
	foreach ($filenamesRemove as $filename) {
		unlink($filename);
	}
}

// For each part I run a python process with a start and an end byte
for($i=0; $i < $opt['p']; $i++) {
	echo "Start p $i\n";
	do{
		// Get the number of the python scripts running
		exec("ps -a | grep 'read.py' | grep -v grep | wc -l", $output);
		$launched = false;
		// If it's full, I will wait
		if((int)(end($output)) <= SIMULTANEOUS_PROCESS) {
			// Run the python script
			exec("/usr/bin/python $readFileScriptPython {$opt['n']} $startByte $endByte > /dev/null 2>&1 &");
			sleep(5);
			$launched = true;
			$startByte += $byteIncrement;
			$endByte += $byteIncrement;
		}
	}while($launched == false);
}

// Process the results file only when the python complete the whole processing
do{
	$files = glob(dirname(__FILE__).'/results/result*');
	if(count($files) == $opt['p']){
		$largestAbs = array();
		foreach ($files as $file){
			$largestFile = json_decode(file_get_contents($file), true);
			$largestAbs = array_unique(array_merge($largestAbs, $largestFile));
			arsort($largestAbs);
			$largestAbs = array_slice($largestAbs, 0, $opt['n']);
		}
		// Print out the largest nums from all files (20GB)
		print_r($largestAbs);
	}

}while(count($files) < $opt['p']); // I Will wait all the parts to be finished

?>