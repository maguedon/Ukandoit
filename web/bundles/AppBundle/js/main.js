$(document).ready(function(){
	$('.parallax').parallax();


	    // toastr options

/*    toastr.options = {
    	"closeButton": true,
    	"debug": false,
    	"newestOnTop": false,
    	"progressBar": false,
    	"positionClass": "toast-top-center",
    	"preventDuplicates": false,
    	"onclick": null,
    	"showDuration": "1000",
    	"hideDuration": "1000",
    	"timeOut": "5000",
    	"extendedTimeOut": "1000",
    	"showEasing": "swing",
    	"hideEasing": "linear",
    	"showMethod": "fadeIn",
    	"hideMethod": "fadeOut"
    }
*/
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
		$(".underHeader").css("display","none");
	}
}

/* -- OVERLAY MENU -- */

$(document).ready(function() {
});
$(".nav-toggle").click(function() {
    $(this).toggleClass("active");
    $(".overlay-boxify").toggleClass("open");
});
$(".overlay ul li a").click(function() {
    $(".nav-toggle").toggleClass("active");
    $(".overlay-boxify").toggleClass("open");
});
$(".overlay").click(function() {
    $(".nav-toggle").toggleClass("active");
    $(".overlay-boxify").toggleClass("open");
});

$(document).ready(function() {
    $('#menu_select').material_select();
  });

$('.select_menu_tab').mouseenter(function(){
    $(".select_menu_tab").css("height", "200");

    $(".select_menu_tab ul").css("display", "block");
    $(".select_menu_tab ul").css("width", "92px");
    $(".select_menu_tab ul").css("position", "absolute");
    $(".select_menu_tab ul").css("top", "0px");
    $(".select_menu_tab ul").css("left", "0px");
    $(".select_menu_tab ul").css("opacity", "1");
    $(".select_menu_tab ul").addClass('active');
    $(".select_menu_tab input").addClass('active');
});

$('.select_menu_tab').mouseleave(function(){
    $(".select_menu_tab").css("height", "48px");

    $(".select_menu_tab ul").css("display", "none");
    $(".select_menu_tab ul").removeClass('active');
    $(".select_menu_tab input").removeClass('active');
});