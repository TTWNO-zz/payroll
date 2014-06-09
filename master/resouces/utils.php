<?php
	function array_to_HTML_table($a=array(), $cols=array(), $header=false){
		$r = "";
		if($header){
			foreach($cols as $x){
				$r.="<th>".$x."</th>";
			}
		}
	}
?>
