<!DOCTYPE html>
<html>
	<head>
		<script src="jQuery.js"></script>
		<script src="javascript.js"></script>
		<link rel="stylesheet" href="style.css"/>
	</head>
	<body>
		<label>See results </label>
		<select>
			<option>After</option>
			<option>All</option>
			<option>Before</option>
		</select>
		<input id="date" placeholder="YYYY-MM-DD" value=<?php echo date("Y-m-d")?> 
		/>
		<br>
		<input id="name" placeholder="Name"/>
		<label>Debug mode</label>
		<input type="checkbox" id="debugMode" />
		<br>
		<button id="refresh">Refresh</button>
		<button id="email">E-mail</button>
		<input type="email" id="Iemail" placeholder="E-mail" autocomplete="true"/>
		<div id="tb">
		
		</div>
		<div id="debug">
			
		</div>
	</body>
</html>
