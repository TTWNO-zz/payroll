<?php
	$fileName = 'version.txt';

	$file = file($fileName);
	$file_lines = [];
	foreach($file as $line_num => $line){
		array_push($file_lines, $line);
	}
	list($VERSION, $NEXT_VERSION, $NEXT_VERSION_DATE, $notice) = $file_lines;

	function next_version_notice(){
		global $NEXT_VERSION, $NEXT_VERSION_DATE, $notice;
		if($notice == 'true'){
			echo "<div id='update'>Notice: Will be updating to $NEXT_VERSION on $NEXT_VERSION_DATE</div>";
		}
	}
?>
