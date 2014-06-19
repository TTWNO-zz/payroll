<!DOCTYPE html>
<?php
	/*
	*	True on testing, false on prodection!
	*/
	ini_set("display_errors",0);
	/* 
	*	external variables $myhost, $myusername, $mypassword,
	*	$mydatabase 
	*/
	require '../resouces/php/vars.php';
	// MySQLDatabase class
	require "../resouces/php/mysql.php";
	/* 	
	*	overriding variables for specific project $myhost
	*		$myusername $mypassword.
	*   Adds variable $mydatabase becuase there is only one database required
	*   	for this project.
	*/
	require "../resouces/php/payroll/vars.php";
	// adds a method to use escape strings
	require "../resouces/php/validString.php";
	// debug function
	require "../resouces/php/debug.php";
	// parse mysql to HTML tabe, or PHP 'List'
	require "../resouces/php/parse/parseMySQL.php";
	// recalibrate timezone for this script
	require "../resouces/php/timezone.php";
	
	/** VARIABLE ASSIGNMENT **/
	// current date in below format
	$uDate = time();
	$date = date("Y-m-d 23:59:59");
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
	$parseSQL = new parseMySQL("");
	if(isset($_POST['test'])){
		$var = $_POST['test'];
		$name = $_POST['name'];
		$db = new MySqlDatabase($myhost,
								$myusername,
								$mypassword,
								$mydatabase);
		$mi="minutes in";
		$mo="minutes out";
		$hi="hours in";
		$ho="hours out";
		$d="day";
		$n="notes";
		switch($var) {
			case "h":
				$db->query("SELECT SUM(`hoursIN`) AS Hours FROM `$name` WHERE `io` = \"OUT\"");
				$parse = new parseMySQL($db->getResult());
				$a = $parse->toList("Hours");
				$htmlOutput = $a['Hours'][0];
				break;
			case "m":
				$db->query("SELECT SUM(`minutesIN`) AS Minutes FROM `$name` WHERE `io` = \"OUT\"");
				$parse = new parseMySQL($db->getResult());
				$a = $parse->toList("Minutes");
				$htmlOutput = $a['Minutes'][0];
				break;
			case "a":
				$db->query("SELECT SUM(`hoursIN`) AS Hours FROM `$name`");
				$parse = new parseMySQL($db->getResult());
				$Ha = $parse->toList("Hours");
				$db->query("SELECT SUM(`minutesIN`) AS Minutes FROM `$name`");
				$parse->setResult($db->getResult());
				$Ma = $parse->toList("Minutes");
				fixNumbers();
				$r = $Ha['Hours'][0].":".$Ma['Minutes'][0];
				$htmlOutput = $r;
				break;
			case "v":
				$db->query("SELECT 
								DATE(FROM_UNIXTIME(`unixTimestamp`)) as $d,
								SUM(`minutesIN`) as `$mi`,
								SUM(`minutesOUT`) as `$mo`,
								SUM(`hoursIN`) as `$hi`,
								SUM(`hoursOUT`) as `$ho`,
								`notes` as notes
							FROM `$name`
							GROUP BY $d");
				$parseSQL->setResult($db->getResult());
				$e = $parseSQL->toHTML("$d","$mi","$mo","$hi","$ho","$n");
				$htmlOutput = $e;
				break;
			case "s":
				// the HTML that will be output at the top of <body>
				$htmlOutput = "";
				// runs mysql query on database
				$db->query("SELECT
	`date`,
	`unixTimestamp`,
	`notes`,
	IF(HOUR(SEC_TO_TIME(SUM(TIMEDIFF(time_out,time_in))))>8,
  		 DATE_SUB(TIMEDIFF(time_in,time_out), INTERVAL '08:00:00' HOUR_SECOND),
  		 0) as overtime,
	SEC_TO_TIME(SUM(TIMEDIFF(time_in,time_out))) as normal_hours
FROM `$name`
WHERE `timestamp` BETWEEN '$startDate' AND '$endDate'
ORDER BY `date`");
				$parseSQL->setResult($db->getResult());
				$htmlOutput = $parseSQL->toHTML("date","normal_hours","overtime");
				//work in progress
				break;
			default:
				die("ERROR!");
		}
		$db->close();
	}else{}
?>
<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript" src="javascript.js"></script>
		<link rel="stylesheet" href="style.css"/>
	</head>
	<body>
		<?php
			/*Prints any html ouput there may be!*/
			echo $htmlOutput;
		?>
		<form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?> method="POST" accept-charset="utf-8">
			<select name="test">
				<option value="h">Get Hours</option>
				<option value="m">Get Minutes</option>
				<option value="a">Get All</option>
				<option value="v">Validate Manualy</option>
				<option value="s">Super Awesome Mode</option>
			</select>
			<br>
			<label>Name: </label>
			<input type="text" placeholder="Name" name="name" value=<?php echo "\"$name\""?>/>
			<br>
			<label>From:</label>
			<input type="text" placeholder="Start date" name="startDate" value=<?php echo "\"$twoWeeksBeforeDate\""?> />
			<br>
			<label>To:</label>
			<input type="text" placeholder="End date" name="endDate" value=<?php echo "\"$date\""?> />
			<br>
			<button type="submit">Send</button>
		</form>
	</body>
</html>