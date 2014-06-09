<?php
	function debug($pre, $message, $level=1){
		global $DEBUG_LEVEL;
		if($DEBUG_LEVEL>=$level){
			echo "${pre}DEBUG ($level): $message";
		}
	}
?>
