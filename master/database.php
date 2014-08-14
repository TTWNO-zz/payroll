<?php
	//require these files from /var/www/php/
	require "./resouces/php/vars.php";
	// TODO: Replacd w/ Adodb
	require "./resouces/php/mysql.php";
	require "./resouces/php/payroll/vars.php";
	require "./resouces/php/timezone.php";
	// TODO: Replace w/ Adodb
	require "./resouces/php/parse/parseMySQL.php";
	// TODO: Replace w/ Adodb
	require "./resouces/php/validateString.php";

	/*
	*	No errros will be shown unless it is set to "On"
	*/
	ini_set("display_errors", "Off");

	/*
	*	This will send a javascript error message with html
	*	back to the client from php to javascript that then adds it
	*	to the webpage to be executed.
	*	See js/javascript.js
	*/
	function dieWithMessage($message, $displayMessage, $back){
		echo("<script>alert('".$message."')</script>"); echo($displayMessage);
		if($back){
			echo("<script>window.history.back()</script>");
	 		die();
	 	}
	}
	if(!$_POST['io']){
		dieWithMessage("Must sign in or out","Failed: no in or out specified",true);
	}
	if(!$_POST['name']){
		dieWithMessage("Must provide name","Failed: no name",true);
	}
	
	$name = validate($_POST['name']);
	$io = validate($_POST['io']);
	$notes = validate($_POST['notes']);
	$dateF = date("M d (D)");

	// Create database $db with variables imported from $root/php/payroll/vars.php
	// TODO: replace with ADONewConnection()
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
	`notes` VARCHAR(700) NOT NULL,
	`sID` INT(5) PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`hour` INT(2) NOT NULL,
	`minute` INT(2) NOT NULL,
	`time_in` INT(2) NOT NULL,
	`time_out` INT(2) NOT NULL
	)";

	$db->query("SELECT *
		  		FROM `$name`
		  		ORDER BY `timestamp` DESC
		 	    LIMIT 1");
	$mysql = new parseMySQL($db->getResult());
	$e = $mysql->toList("io","unixTimestamp");
	if ($e['io'][0]==$io) {
		$lowerIO = strtolower($io);
		echo "<script>var conf=confirm(\"Are you sure you want to sign $lowerIO\\nYou did this last time!\");if (conf==true){}else{window.history.back();}</script>";
	}
	
	/*Calculate time difference*/
	if(isset($e['io'][0])){
		$ioSuf = $e['io'][0];
	}else{
		$ioSuf = "OUT";
	}
	$ioSuf = strtolower($ioSuf);
	$time_diff = intval(time())-$e['unixTimestamp'][0];

	$querys[2] = "INSERT INTO `".$name."`
		   (`unixTimestamp`,
		    `date`,
		    `io`,
		    `notes`,
		    `hour`,
		    `minute`,
		    `time_$ioSuf`
		   ) VALUES (
		   		'".date("U")."'				,
		   		'".date("l F jS Y")."'      ,
		   		'$io'						,
		   		'$notes'					,
		   		'".date("H")."'				,
		   		'".date("i")."'				,
		   		'".$time_diff."'
		   )";
	
	// TODO: Replace this crap-ton of code with a few lines using ADONewConnection->functions()
	$correctQuerys = 0;
	$totalQuerys = sizeof($querys);
	foreach($querys as $x){
		$db->query($x);
		if(!$db->getResult()){
			$correctQuerys++;
			echo "<p style='color:red;font-size:50px;'>Error: <a href=\"mailto:taitskywalker@gmail.com\">Contact Tait</a> he knows what it means: </p>";
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
