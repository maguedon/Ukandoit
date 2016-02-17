$(document).ready(function() {

    // -------------- Initialisation du parallax --------------
    $('.parallax').parallax();

     // -------------- Activation des balises select --------------
    $('select').material_select();

    // -------------- Activation dropdown menu --------------
    $(".dropdown-button").dropdown();

    // -------------- Remonte le underHeader si chargement en milieu de page --------------
    $(window).load(function() {
        if (document.body.scrollTop !== 0 || document.documentElement.scrollTop !== 0) {
            $(".underHeader").slideUp('fast');
        }
    });

    // -------------- Remonte le underHeader quand scroll --------------
    window.onscroll = function(e) {
        if (window.innerWidth > 768) {
            if (document.body.scrollTop !== 0 || document.documentElement.scrollTop !== 0) {
                $(".underHeader").slideUp('fast');
            } else {
                $(".underHeader").slideDown();
            }
        } else {
            $(".underHeader").css("display", "none");
        }
    }

/* -------------- OVERLAY MENU -------------- */

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


// -------------- ToolTipe Avatar dans menu et bouton deconnection --------------

    $('.tooltipped').tooltip({
        delay: 50
    });


// --------------Animation des bouton sociaux --------------
    $('.fb_logo').mouseenter(function() {
        $(this).addClass('animated pulse infinite');
    });
    $('.fb_logo').mouseleave(function() {
        $(this).removeClass('animated pulse infinite');
    });
    $('.tw_logo').mouseenter(function() {
        $(this).addClass('animated pulse infinite');
    });
    $('.tw_logo').mouseleave(function() {
        $(this).removeClass('animated pulse infinite');
    });
    $('.round_tab').not(".round_tab.active").mouseenter(function() {
        $(this).addClass('animated pulse infinite');
    });
    $('.round_tab').not(".round_tab.active").mouseleave(function() {
        $(this).removeClass('animated pulse infinite');
    });
    $('#inscription-button').mouseenter(function() {
        $(this).addClass('animated tada');
    });
    $('#inscription-button').mouseleave(function() {
        $(this).removeClass('animated tada');
    });
    $(document).ready(function() {
        $('ul#tabs_add_defis').tabs();
    });

/* -------------- French configuration pour les Datepicker -------------- */
    (function(factory) {
        if (typeof define === "function" && define.amd) {
            // AMD. Register as an anonymous module.
            define(["../jquery.ui.datepicker"], factory);
        } else {
            // Browser globals
            factory(jQuery.datepicker);
        }
    }(function(datepicker) {
        datepicker.regional['fr'] = {
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'
            ],
            dayNames: ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
            dayNamesShort: ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        datepicker.setDefaults(datepicker.regional['fr']);
        return datepicker.regional['fr'];
    }));
    $(".datepicker").datepicker({
        dateFormat: 'dd/mm/yy',
        firstDay: 1
    }).attr("readonly", "readonly");

// -------------- Modal chargement data quand ajout defis --------------

//$('.modal-trigger').leanModal();

// -------------- Ajout challenge Bloquer champ nbPas quand champ kilometre avec value et vice versa --------------
    $("#select_pas_km").change(function() {
        if ($(this).val() == "pas") {
            $("#inputKm2").css("display", "none");
            $("#inputSteps2").css("display", "block");
        } else {
            $("#inputSteps2").css("display", "none");
            $("#inputKm2").css("display", "block");
        }
    });

// -------------- A defnir --------------

 var modalDefis = $(".modal_defis");

    modalDefis.on("click", function() {
        var defi_id = $(this).attr("value");
        var defi_creator = $(this).attr("creator");
        var url = window.location.protocol + "//" + window.location.host;
        var url_defi = url + '/defi/' + defi_id;
        // generate share btn
        $(".accept_share").remove();
        $(".pick_share").append('<a href="https://twitter.com/share?hashtags=ukandoit&original_referer=https%3A%2F%2Fukandoit.fr%2Fdefi%2F67%2F&ref_src=twsrc%5Etfw&text=J%27ai%20relev%C3%A9%20le%20d%C3%A9fi%20de%20'+defi_creator+'%20sur%20Ukandoit.fr%20!%20Rel%C3%A8verez-vous%20le%20challenge%20%3F&tw_p=tweetbutton&url='+url_defi+'&via=ukando_it" class="accept_share btn_send waves-effect waves-light btn modal-trigger" target="_blank" data-via="ukando_it" data-hashtags="ukandoit" >Participer et Twitter !</a>');

        $(".pick_share").on("click", function() {
                $("#form_defi_send").trigger("click");
        });

        $("#form_defi_send").on("click", function() {
            var objet_id = $('input[name=object_form]:checked', '#form_defi').val();
            location.href = url + '/defis/' + defi_id + '/' + objet_id + '/accepted';
        });

        $('#modal1').openModal();

        var leanOverlay = $(".lean-overlay");

        leanOverlay.each(function() {
            $(this).click(function(event) {
                leanOverlay.each(function() {
                    $(this).remove();
                });
            });
        });
    });

    $('.card-action.modal_defis a').click(function(event) {
        event.preventDefault();
    });

    $(".home .best-challenges .defi").hide();
    if ($(".home").has(".current-challenges")) {
        $(".home .current-challenges .defi").hide();
    }

    //Gestion de l'affichage des défis de l'accueil
    $(".home .defis div.round_tab").click(function() {
        var challengeType = $(this).attr("name");

        if(challengeType == "redirect-login"){
            location.href = window.location.protocol + "//" + window.location.host + "/login#login-page";
        }else{
            $(".home .defis div.round_tab").removeClass("active");
            $(this).addClass("active");
            $(".home .defis .affichage-defis .defi").hide();
            $(".home .defis ." + challengeType + " .defi").show();
        }

    });

// --------------  percent score circle -------------- //


/*$(function(){
    var $ppc = $('.progress-pie-chart'),
    percent = parseInt($ppc.data('percent')),
    deg = 360*percent/100;
    if (percent > 50) {
        $ppc.addClass('gt-50');
    }
    $('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
    $('.ppc-percents span').html(percent+'%');
});
*/

/**!
 * easyPieChart
 * Lightweight plugin to render simple, animated and retina optimized pie charts
 *
 * @license Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * @author Robert Fleischmann <rendro87@gmail.com> (http://robert-fleischmann.de)
 * @version 2.0.1
 **/
!function(){var a=function(a,b){var c=document.createElement("canvas");"undefined"!=typeof G_vmlCanvasManager&&G_vmlCanvasManager.initElement(c);var d=c.getContext("2d");if(c.width=c.height=b.size,a.appendChild(c),window.devicePixelRatio>1){var e=window.devicePixelRatio;c.style.width=c.style.height=[b.size,"px"].join(""),c.width=c.height=b.size*e,d.scale(e,e)}d.translate(b.size/2,b.size/2),d.rotate((-0.5+b.rotate/180)*Math.PI);var f=(b.size-b.lineWidth)/2;b.scaleColor&&b.scaleLength&&(f-=b.scaleLength+2);var g=function(a,b,c){c=Math.min(Math.max(0,c||1),1),d.beginPath(),d.arc(0,0,f,0,2*Math.PI*c,!1),d.strokeStyle=a,d.lineWidth=b,d.stroke()},h=function(){var a,c,e=24;d.lineWidth=1,d.fillStyle=b.scaleColor,d.save();for(var e=24;e>=0;--e)0===e%6?(c=b.scaleLength,a=0):(c=.6*b.scaleLength,a=b.scaleLength-c),d.fillRect(-b.size/2+a,0,c,1),d.rotate(Math.PI/12);d.restore()};Date.now=Date.now||function(){return+new Date};var i=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||function(a){window.setTimeout(a,1e3/60)}}();this.clear=function(){d.clearRect(b.size/-2,b.size/-2,b.size,b.size)},this.draw=function(a){this.clear(),b.scaleColor&&h(),b.trackColor&&g(b.trackColor,b.lineWidth),d.lineCap=b.lineCap;var c;c="function"==typeof b.barColor?b.barColor(a):b.barColor,a>0&&g(c,b.lineWidth,a/100)}.bind(this),this.animate=function(a,c){var d=Date.now();b.onStart(a,c);var e=function(){var f=Math.min(Date.now()-d,b.animate),g=b.easing(this,f,a,c-a,b.animate);this.draw(g),b.onStep(a,c,g),f>=b.animate?b.onStop(a,c):i(e)}.bind(this);i(e)}.bind(this)},b=function(b,c){var d,e={barColor:"#ef1e25",trackColor:"#f9f9f9",scaleColor:"#dfe0e0",scaleLength:5,lineCap:"round",lineWidth:3,size:110,rotate:0,animate:1e3,renderer:a,easing:function(a,b,c,d,e){return(b/=e/2)<1?d/2*b*b+c:-d/2*(--b*(b-2)-1)+c},onStart:function(){},onStep:function(){},onStop:function(){}},f={},g=0,h=function(){this.el=b,this.options=f;for(var a in e)e.hasOwnProperty(a)&&(f[a]=c&&"undefined"!=typeof c[a]?c[a]:e[a],"function"==typeof f[a]&&(f[a]=f[a].bind(this)));f.easing="string"==typeof f.easing&&"undefined"!=typeof jQuery&&jQuery.isFunction(jQuery.easing[f.easing])?jQuery.easing[f.easing]:e.easing,d=new f.renderer(b,f),d.draw(g),b.dataset&&b.dataset.percent&&this.update(parseInt(b.dataset.percent,10))}.bind(this);this.update=function(a){return a=parseInt(a,10),f.animate?d.animate(g,a):d.draw(a),g=a,this}.bind(this),h()};window.EasyPieChart=b}();

var options = {
  scaleColor: false,
  //trackColor: 'rgba(255,255,255,0.3)',
  trackColor: 'rgba(245,245,245,1)',
  barColor: '#d82027',
  lineWidth: 6,
  lineCap: 'butt',
  size: 95
};

window.addEventListener('DOMContentLoaded', function() {
  var charts = [];
  [].forEach.call(document.querySelectorAll('.chart'),  function(el) {
    charts.push(new EasyPieChart(el, options));
  });
});

// --------------  toastr options -------------- //

    /*
    toastr.options = {
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

// -------------- Confirmation suppression objet ----------- //
    $(".objects a[name='delete']").click(function(){
        return confirm('Etes-vous sûr de vouloir supprimer cet objet ?');
    });

});