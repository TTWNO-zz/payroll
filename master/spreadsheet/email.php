O<?php
	function validate($x){
		$x = trim($x);
		$x = stripslashes($x);
		$x = htmlspecialchars($x);
		return $x;
	}
	
	$style = "
		<style>
			th,td{padding:10px;}
		</style>
	";
	
	$to = validate($_POST['email']);
	
	$subject = "Spreadsheets";
	$message = ("<html><head>".$style."</head><body><div>".$_POST['message']."</div></body></html>");
	
	$headers = "MIME-Version: 1.0 \r\n";
	$headers.= "Content-type: text/html \r\n";
	
	$headers.= "To: ".$to." \r\n";
	
	$headers.= "From: Tait <tait.skywalker@gmail.com> \r\n";
	
	
	
	if(mail($to,$subject,$message,$headers,$to)){
		echo("YES");
	}else{
		echo("NO!");
	}
?>
