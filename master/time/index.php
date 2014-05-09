<!DOCTYPE html>
<?php
	// ini_set("display_errors",0);
	require '../../../../php/vars.php';
	require "$root/php/mysql.php";
	require "$root/php/payrol/vars.php";
	require "$root/php/validString.php";
	require "$root/php/debug.php";
	require "$root/php/parse/parseMySQL.php";
	require "$root/php/timezone.php";

	if(isset($_GET['d'])){
		if(is_numeric($_GET['d'])){
			$DEBUG_LEVEL = $_GET['d'];
			echo "debug level set to $DEBUG_LEVEL";
		}else{
			$DEBUG_LEVEL = 0;
			echo "Debug level set to 0";
		}
	}else{
		$DEBUG_LEVEL = 0;
	}

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
		switch($var) {
			case "h":
				$db->query("SELECT SUM(`hoursIN`) AS Hours FROM `$name` WHERE `io` = \"OUT\"");
				$parse = new parseMySQL($db->getResult());
				$a = $parse->toList("Hours");
				echo $a['Hours'][0];
				break;
			case "m":
				$db->query("SELECT SUM(`minutesIN`) AS Minutes FROM `$name` WHERE `io` = \"OUT\"");
				$parse = new parseMySQL($db->getResult());
				$a = $parse->toList("Minutes");
				echo $a['Minutes'][0];
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
				echo $r;
				break;
			case "v":
				echo "Not yet fully implemented!";
				$db->query("SELECT 
								DATE(FROM_UNIXTIME(`unixTimestamp`)) as d,
								SUM(`minutesIN`) as mi,
								SUM(`minutesOUT`) as mo,
								SUM(`hoursIN`) as hi,
								SUM(`hoursOUT`) as ho,
								`notes` as notes
							FROM `$name`
							GROUP BY d");
				$parseSQL->setResult($db->getResult());
				$e = $parseSQL->toHTML("mi","mo","d","hi","ho","notes");
				echo "$e";
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
		<form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?> method="POST" accept-charset="utf-8">
			<select name="test">
				<option value="h">Get Hours</option>
				<option value="m">Get Minutes</option>
				<option value="a">Get All</option>
				<option value="v">Validate Manualy</option>
			</select>
			<br>
			<label>Name: </label>
			<input type="text" placeholder="Name" name="name" value=<?php echo "\"$sname\"" ?> />
			<br>
			<label>From:</label>
			<input type="text" placeholder="Start date" name="startDate" value=<?php echo date("Y-m-d", strtotime("-2 weekss"))?> />
			<br>
			<label>To:</label>
			<input type="text" placeholder="End date" name="endDate" value=<?php echo date("Y-m-d")?> />
			<br>
			<button type="submit">Send</button>
		</form>
	</body>
</html>
