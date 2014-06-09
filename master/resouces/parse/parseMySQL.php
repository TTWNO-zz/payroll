<?php
	class parseMySQL{
		private $sql;
		function __construct($sql){
			$this->sql = $sql;
		}
		function toList(){
			$cols = func_get_args();
			$array = array();
			$localresult = $this->sql;
			while($row = mysqli_fetch_array($localresult)){
				$count = 0;
				foreach($cols as $x){
					$array[$x][$count] = $row[$x];
				}
				$count++;
			}
			return $array;
		}
		function toArray(){
			return $this->toList(func_get_args());
		}
		function toHTML(){
			$cols = func_get_args();
			$localresult = $this->sql;
			$r = "";
			$r.= "<table border=1><tbody>";
				foreach($cols as $x){
					$r.= "<th>".$x."</th>";
				}
				while($row = mysqli_fetch_array($localresult)){
					$r.= "<tr>";
					foreach($cols as $x){
						$r.= "<td>".$row[$x]."</td>";
					}
					$r.= "</tr>";
				}
			$r.= "</tbody></table>";
			return $r;
		}
		function setResult($sql){
			$this->sql = $sql;
		}
	}
?>