<?php
	function escape_special_chars($s){
		$s = str_replace('\'', "\\'", $s);
		$s = str_replace("\"", '\\"', $s);
		$s = stripslashes($s)
		$s = str_replace(`\``, "\\`", $s);
	}
?>