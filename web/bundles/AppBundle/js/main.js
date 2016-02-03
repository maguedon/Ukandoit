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

/* French initialisation for the jQuery UI date picker plugin. */
/* Written by Keith Wood (kbwood{at}iinet.com.au),
              Stéphane Nahmani (sholby@sholby.net),
              Stéphane Raimbault <stephane.raimbault@gmail.com> */
(function( factory ) {
    if ( typeof define === "function" && define.amd ) {

        // AMD. Register as an anonymous module.
        define([ "../jquery.ui.datepicker" ], factory );
    } else {

        // Browser globals
        factory( jQuery.datepicker );
    }
}(function( datepicker ) {
    datepicker.regional['fr'] = {
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
        dayNamesShort: ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'],
        dayNamesMin: ['D','L','M','M','J','V','S'],
        weekHeader: 'Sem.',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''};
    datepicker.setDefaults(datepicker.regional['fr']);

    return datepicker.regional['fr'];

}));

$("#new_challenge_endDate").datepicker({
    dateFormat: 'dd/mm/yy',
    firstDay:1
}).attr("readonly","readonly");