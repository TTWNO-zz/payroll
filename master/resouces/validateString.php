<?php
	function validate($s){
		$s = trim($s);
		$s = stripslashes($s);
		$s = htmlspecialchars($s);
		$s = str_replace("'", "&#39;", $s);
		$s = str_replace("\"", "&#34;", $s);
		return $s;
	}
?>
