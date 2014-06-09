<?php
	class MySQLDatabase{
		private $con;
		private $result;
		public function __construct($host,$user,$pass,$db){
			$this->con = mysqli_connect($host,$user,$pass,$db);
		}
		public function query($query){
			$this->result = mysqli_query($this->con, $query);
		}
		/*
		public function querys(){
			foreach($querys as $x){
			$db->query($x);
			if(!$db->getResult()){
				$correctQuerys++;
				echo "<p style='color:red;font-size:50px;'>Error: Contact Tait he knows what it means: </p>".
				$db->error().
				"<br><br>On query: $correctQuerys/$totalQuerys ".$x."<br>var_dump: ";
				var_dump($x);
				die("<br>ERROR!");
			}else{
				$correctQuerys++;
				echo "Executed succsesfuly $correctQuerys/$totalQuerys<br>";
			}
		}
		*/
		public function fetch_array(){
			return mysqli_fetch_array($this->result);
		}
		public function getResult(){
			return $this->result;
		}
		public function getSQLi(){
			return $this->con;
		}
		public function close(){
			mysqli_close($this->con);
		}
		public function connect_errno(){
			return mysqli_connect_errno($this->con);
		}
		public function connect_error(){
			return mysqli_connect_error($this->con);
		}
		public function error(){
			return mysqli_error($this->con);
		}
		public function returnHTMLTable(){
			trigger_error("Please use parser functions insted", E_USER_NOTICE);
			$cols = func_get_args();
			$localresult = $this->results;
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
		public function returnPHPArray(){
			trigger_error("Please use parser functions insted", E_USER_NOTICE);
			$cols = func_get_args();
			$array = array();
			$localresult = $this->result;
			while($row = mysqli_fetch_array($localresult)){
				$count = 0;
				foreach($cols as $x){
					$array[$x][$count] = $row[$x];
				}
				$count++;
			}
			return $array;
		}
	}
?>
