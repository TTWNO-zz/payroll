$(document).ready(function(){
	$(".specs").hide();
	$(".specsButton").click(function(){
		$(this).nextAll("div").toggle();
	});
});