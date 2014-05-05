<?php
	require '../../php/mysql.php';
	$cutOff = "23:59:59";
	$date = htmlspecialchars($_POST['date']);
	$beforeAfter = htmlspecialchars($_POST['ab']);
	$name = htmlspecialchars($_POST['name']);
	$dm = htmlspecialchars($_POST['dm']);

	function isab(){
		global $beforeAfter;
		global $cutOff;
		if ($beforeAfter == "After") {
			$cutOff = "00:00:00";
			return '>';
		} elseif($beforeAfter == "Between") {
			return "BETWEEN";
		}
		else{
			return '<';
		}
	}
	
	$ab = isab();

	if(is_null($dm)){
		$query = "SELECT (`timeFormatted`) FROM `$name` WHERE `timestamp` $ab \"$date $cutOff\" ORDER BY `timestamp` DESC";
	}else{
		$query = "SELECT * FROM `$name` WHERE `timestamp` $ab \"$date $cutOff\" ORDER BY `timestamp` DESC";
	}
	
	$database = new MySQLDatabase("localhost","root","HTML%!HTML$","log");
	$database->query($query);
	$table = $database->returnHTMLTable("io");
	echo $table;
	$database->close();
?>
