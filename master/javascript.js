$(document).ready(function(){
	// alert("working...");
	$("#notes").keyup(function(){
		$("#charsleft").text(150-($(this).val().length));
	});
});
