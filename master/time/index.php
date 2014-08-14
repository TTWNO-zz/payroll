<!DOCTYPE html>
<?php
	/*
	*	True on testing, false on prodection!
	*/
	ini_set("display_errors",1);
	/* 
	*	external variables $myhost, $myusername, $mypassword,
	*	$mydatabase 
	*/
	require '../resouces/php/vars.php';
	
	/*
		ADODB, Replacement for past system of custum files,
		functions are now well documented, and understood!	
	*/
	include('../resouces/adodb5/adodb.inc.php');
	include('../resouces/adodb5/tohtml.inc.php');
	include('../resouces/adodb5/adodb-exceptions.inc.php');

	/* 	
	*	overriding variables for specific project $myhost
	*		$myusername $mypassword.
	*   Adds variable $mydatabase becuase there is only one database required
	*   	for this project.
	*/
	require "../resouces/php/payroll/vars.php";

	// PHP list to HTML table function
	require "./array2table.php";

	/* adds a method to use escape strings
		Old: require "../resouces/php/validString.php";
		Replaced by ADONewConnection->qstr($str)
	*/
	// debug function
	require "../resouces/php/debug.php";
	// recalibrate timezone for this script
	require "../resouces/php/timezone.php";
	
	/** VARIABLE ASSIGNMENT **/
	// current date in below format
	$uDate = time();
	$date = date("Y-m-d 00:00:00",strtotime("+1 day"));
	// two weeks ago in current format
	$twoWeeksBeforeDate = date("Y-m-d 00:00:00",strtotime("-2 weeks -1 day"));

	// 1st date to get in between
	$startDate = $_POST['startDate'];
	$uStartDate = new DateTIme($startDate);

	// 2nd date to get in between
	$endDate = $_POST['endDate'];
	$uEndDate = new DateTime($endDate);

	// debuging can be set as d through the URL
	if(isset($_GET['d'])){
		if(is_numeric($_GET['d'])){
			$DEBUG_LEVEL = $_GET['d'];
			echo "debug level set to $DEBUG_LEVEL";
		}else{}
	}else{
		$DEBUG_LEVEL = 0;
	}
	// n (name) can be set through url as well
	if(isset($_GET['n'])){
		$name = urldecode($_GET['n']);
		$sname = $name;
	}else{
		$sname = "";
	}

	function fixNumbers(){
		global $Ma;
		global $Ha;
		if($Ma['Minutes'][0]>=60){
			$Ma['Minutes'][0]-=60;
			$Ha['Hours'][0]+=1;
			if($Ma['Minutes'][0]>=60){
				fixNumbers();
			}
		}else{return;}
	}
	if(isset($_POST['mode'])){
		$htmlOutput = "";
		$mode = $_POST['mode'];
		$name = $_POST['name'];
		try{
			$db = ADONewConnection('mysqli://root:HTML%!HTML$@localhost/log?');
		}
		catch(Exception $e){
			//print_r($e);
		}
		$db->debug = false;
		$rs = $db->Execute("SELECT * FROM `$name` WHERE `timestamp` BETWEEN '$startDate' AND '$endDate'");
		// Printing
		rs2html($rs,'border=2');
		$dateDiff = date_diff(date_create($endDate),date_create($startDate));
		$interval = $dateDiff->format("%a");
		for ($i=0; $i < $interval; $i++) { 
			$day = date("Y-m-d",strtotime("-$i days"));
			$result = $db->Execute("SELECT SEC_TO_TIME(SUM(time_in)) as `Standard`,
										   IF(HOUR(SEC_TO_TIME(SUM(time_in)))>8,
          										SEC_TO_TIME(SUM(time_in)-28800),
          										'00:00:00') AS `Overtime`
										   FROM `$name`
										   WHERE `timestamp` BETWEEN '$day 00:00:00' AND '$day 23:59:59'
										   GROUP BY `date`");
			$array = array2table($result->getArray());
			echo("<button class=\"specsButton\">Details $day</button><br><div class=\"specs\">$array</div>");
		}
		echo '<br>';
		// Totals
		$result = $db->Execute("SELECT SEC_TO_TIME(SUM(time_in)) as `Standard`,
										   IF(HOUR(SEC_TO_TIME(SUM(time_in)))>8,
          										SEC_TO_TIME(SUM(time_in)-28800),
          										'00:00:00') AS `Overtime`
										   FROM `$name`
										   WHERE `timestamp` BETWEEN '$startDate' AND '$endDate'
										   GROUP BY `date`");
		$array = array2table($result->getArray());
		echo("<button class=\"specsButton\" id=\"totals\">Grand Totals</button><br><div class=\"specs\">$array</div>");
		// Closing
		$rs->close();
		$db->close();
	}else{}
?>
<html>
	<head>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
		<link rel="stylesheet" href="style.css"/>
	</head>
	<body>
		<form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?> method="POST" accept-charset="utf-8">
			<select name="mode">
				<option value="h" disabled="">Get Hours</option>
				<option value="m" disabled="">Get Minutes</option>
				<option value="a" disabled="">Get All</option>
				<option value="v" disabled="">Validate Manualy</option>
				<option value="s">Super Awesome Mode</option>
			</select>
			<br>
			<label>Name: </label>
			<input type="text" placeholder="Name" name="name" value=<?php echo("\"$sname\"") ?> />
			<br>
			<label>Start:</label>
			<input type="text" placeholder="Start date" name="startDate" value=<?php echo "\"$twoWeeksBeforeDate\""?> />
			<br>
			<label>End:</label>
			<input type="text" placeholder="End date" name="endDate" value=<?php echo "\"$date\""?> />
			<br>
			<button type="submit">Send</button>
		</form>
	</body>
</html>