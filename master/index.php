<?php
	ini_set('display_errors',0);
	if(isset($_GET['n'])){
		$name = urldecode($_GET['n']);
		$sname = str_replace("\"","",$name);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css"/>
		
		<script src="jQuery.js"></script>
		<script src="javascript.js"></script>
		<title>Sign In / Out <?php echo($sname)?></title>
	</head>
	<body>
		<div id="website">
			<div id="stuff">
				<h1>
					Time Sign In For <?php echo($sname)?>
				</h1>
				<p class="red">* Required</p>
				<form action="database.php" autocomplete="true" method="POST">
					<label class="red">*</label>
					<input required type="text" name="name" placeholder="Name" value=<?php echo "\"$name\""?> id="name" />
					<div id="guess"></div>
					<br>
					<div class="red">*</div>
					<div id="question">
						<span id="IN">
							<input required class="bigButton" type="radio" name="io" value="IN"/>
							<label>IN</label>
						</span>
						<span id="OUT">
							<label>OUT</label>
							<input required class="bigButton" type="radio" name="io" value="OUT"/>
						</span>
					</div>
					<br><br>
					<p>NOTES</p>
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
