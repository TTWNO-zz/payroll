$(document).ready(function(){
	var upArrow = '&#x25B2'
	var dowArrow = '&#x25BC'
	$(".specs").hide();
	$('#totalSpecs').show();
	$(".specsLabel").click(function(){
		$(this).next('.specs').slideToggle();
	});
	$("#showAll").click(function(){
		$(".specs").slideDown();
	});
	$("#hideAll").click(function(){
		$(".specs").slideUp();
	});
	pass = localStorage['passwd'] || "poo";
	if(pass != 'cheese'){
		pass = prompt("Password:\n","");
		if (pass != "cheese"){
			window.history.back();
		}
		localStorage['passwd'] = pass;
	}
	
});
