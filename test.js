window.addEventListener("load", function(e)
 {
 	setTimeout(function()
 		{
 			var id = "#ancre";
		 	var speed = "slow";
		 	$("html, body").animate({scrollTop : $(id).offset().top}, speed);
		 } ,1500);
 })