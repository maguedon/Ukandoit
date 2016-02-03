//Initialisation du parallax
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

	//Permet d'afficher les balises select
	$('select').material_select();

    //Activation dropdown menu
    $(".dropdown-button").dropdown();

    $(".card-action").click(function() {
        var id_defi = $(this).attr('id');

        toastr.success("Vous avez relevé le défi " +   id_defi );
    });
    $('.card-action a').click(function(event){
        event.preventDefault();
    });


});

//Remonte le underHeader si chargement en milieu de page
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

//ToolTipe Avatar dans menu et bouton deconnection
$(document).ready(function(){
    $('.tooltipped').tooltip({delay: 50});
});

//Animation des bouton sociaux
$('.fb_logo').mouseenter(function(){
    $(this).addClass('animated pulse infinite');
});

$('.fb_logo').mouseleave(function(){
    $(this).removeClass('animated pulse infinite');
});

$('.tw_logo').mouseenter(function(){
    $(this).addClass('animated pulse infinite');
});

$('.tw_logo').mouseleave(function(){
    $(this).removeClass('animated pulse infinite');
});