<?php
	require '../php/mysql.php';
	require '../php/payrol/vars.php';
	require '../php/timezone.php';
	require '../php/parse/parseMySQL.php';
	require '../php/validateString.php';

	function dieWithMessage($message, $displayMessage, $back){
		echo("<script>alert('".$message."')</script>"); echo($displayMessage);
		if($back){ 
			echo("<script>window.history.back()</script>");
	 		die();
	 	}
	}	
	if(!$_POST['io']){
		dieWithMessage("Must sign in or out","Failed: io error",true);
	}
	if(!$_POST['name']){
		dieWithMessage("Must provide name","Failed: name error",true);
	}
	
	$name = validate($_POST['name']);
	$io = validate($_POST['io']);
	$notes = validate($_POST['notes']);
	$dateF = date("M d (D)");

	$db = new MySQLDatabase($myhost,
				$myusername,
				$mypassword,
				$mydatabase);

	// All querys are given to the database
	// from index 0, then 1, then 2 etc...

	$querys[0] = "CREATE DATABASE IF NOT EXISTS `$mydatabase`";

	$querys[1] = "CREATE TABLE IF NOT EXISTS `$name` (
	`timestamp` TIMESTAMP NOT NULL  DEFAULT CURRENT_TIMESTAMP,
	`unixTimestamp` INT(8) NOT NULL,
	`date` VARCHAR(30) NOT NULL,
	`io` VARCHAR(3) NOT NULL,
	`notes` VARCHAR(150) NOT NULL,
	`sID` INT(5) PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`hour` INT(2) NOT NULL,
	`minute` INT(2) NOT NULL,
	`hoursIN` INT(2) NOT NULL,
	`minutesIN` INT(2) NOT NULL,
	`hoursOUT` INT(2) NOT NULL,
	`minutesOUT` INT(2) NOT NULL
	)";

	$db->query("SELECT *
		  		FROM `$name`
		  		ORDER BY `timestamp` DESC
		 	    LIMIT 1");
	$mysqlto = new parseMySQL($db->getResult());
	$e =$mysqlto->toList("hour","minute","io");
	$hInt = intval(date("H"));
	$mInt = intval(date("i"));
	$prevHour = intval($e['hour'][0]);
	$prevMinute = intval($e['minute'][0]);
	$h = $hInt-$prevHour;
	$m = $mInt-$prevMinute;
	// if the current hour is equal to
	// the current hour - the previous hour
	// if the previous hour = NULL then = 0
	if($h == $hInt){
		$h = 0;
		$m = 0;
	}
	if($m < 0){
		$h -= 1;
		$m += 60;
	}
	if($h < 0){
		$h+=24;
	}
	if(isset($e['io'][0])){
		$ioSuf = $e['io'][0];
	}else{
		$ioSuf = "OUT";
	}
	$querys[2] = "INSERT INTO `".$name."`
		   (`unixTimestamp`,
		    `date`,
		    `io`,
		    `notes`,
		    `hour`,
		    `minute`,
		    `hours$ioSuf`,
		    `minutes$ioSuf`
		   ) VALUES (
		   		'".date("U")."'				,
		   		'".date("l F jS Y")."'      ,
		   		'$io'						,
		   		'$notes'					,
		   		'".date("H")."'				,
		   		'".date("i")."'				,
		   		'".$h."' 					,
		   		'".$m."'					
		   )";
	

	$correctQuerys = 0;
	$totalQuerys = sizeof($querys);
	foreach($querys as $x){
		$db->query($x);
		if(!$db->getResult()){
			$correctQuerys++;
			echo "<p style='color:red;font-size:50px;'>Error: Contact Tait he knows what it means: </p>";
			echo "<br><br>On query: $correctQuerys/$totalQuerys ".$x."<br>var_dump: ";
			echo "E: $db->error()";
			echo "CE: $db->connectError()";
			var_dump($x);
			die("<br>ERROR!");
		}else{
			$correctQuerys++;
			echo "Executed succsesfuly $correctQuerys/$totalQuerys<br>";
		}
	}
	echo "E: ".$db->error()."<br>";
	echo "CE: ".$db->connect_error()."<br>";
	
	$db->close();
?>