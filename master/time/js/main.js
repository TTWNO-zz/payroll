$(document).ready(function(){
	$(".specs").hide();
	$(".specsButton").click(function(){
		$(this).next().toggle();
	});
});