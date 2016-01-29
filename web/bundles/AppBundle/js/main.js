$(document).ready(function(){
	$('.parallax').parallax();
});

$(window).load(function()
{
	if(document.body.scrollTop!==0 || document.documentElement.scrollTop!==0){
		$(".underHeader").slideUp('fast');
	}
});

//Remonte le underHeader quand scroll
window.onscroll = function(e){
	if(window.innerWidth > 768)
	{
		if (document.body.scrollTop!==0 || document.documentElement.scrollTop!==0){
			$(".underHeader").slideUp('fast');
		}
		else{
			$(".underHeader").slideDown();
		}
	}
	else{
		$(".underHeader").css("position","absolute");
		$(".underHeader").css("display","block");
	}
}