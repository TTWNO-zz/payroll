<?php
	ini_set('display_errors',0);
	if(isset($_GET['n'])){
		$name = urldecode($_GET['n']);
		$sname = str_replace("\"","",$name);
		$sname_no_space = str_replace(" ", "", $sname);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css"/>
		<link rel="stylesheet" href=<?php echo "\"stylesheet$sname_no_space.css\"";?>/>
		<script src="jQuery.js"></script>
		<script src="javascript.js"></script>
		<title>Sign In / Out <?php echo($sname)?></title>
	</head>
	<body>
		<div id="website">
			<div id="stuff">
				<h1 id="header">
					Sign in/out for <?php echo($sname)?>
				</h1>
				<p class="red">* Required</p>
				<form action="database.php" autocomplete="true" method="POST">
					<label class="red">*</label>
					<input required type="text" name="name" placeholder="Name" value=<?php echo "\"$name\""?> id="name" />
					<br><br>
					<p class="red">*</p>
					<div id="question">
						<span id="IN">
							<input id="buttonIN" required class="bigButton" type="radio" name="io" value="IN"/>
							<label for="buttonIN" class="labelButton">IN</label>
						</span>
						<span id="OUT">
							<input id="buttonOUT" required class="bigButton" type="radio" name="io" value="OUT"/>
							<label for="buttonOUT" class="labelButotn">OUT</label>
						</span>
					</div>
					<textarea rows="8" cols="30" placeholder="Notes" name="notes" maxlength="150" id="notes"></textarea>
					<label id="charsleft">150</label>
					<br>
					<input type="submit" value="Submit" id="submit" />
				</form>
			</div>
			<div id="debug">
			
			</div>
		</div>
	</body>
</html>
