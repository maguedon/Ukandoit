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

    EnableSubmit = function(val) {
        var sbmt = document.getElementById("form_defi_send");
        $(sbmt).removeAttr("disabled");
    }

    var modalDefis = $(".modal_defis");

    modalDefis.on("click", function() {
        var defi_id = $(this).attr("value");
        $("#form_defi_send").on("click", function() {
            var objet_id = $('input[name=object_form]:checked', '#form_defi').val();
            var url = window.location.protocol + "//" + window.location.host + "/web/app_dev.php";
            // remplacer par window.location.protocol + "//" + window.location.host + "/" en prod)
            location.href = url + '/defis/' + defi_id + '/' + objet_id + '/accepted';
            /* $.post("challenges", {var_value: objet_id}, function(data){
            alert("data sent and received: "+data);
            });*/
        });

        $('#modal1').openModal();
        //$(".modal_defis").leanModal();

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
            location.href = window.location.protocol + "//" + window.location.host + "/web/app_dev.php/login#login-page";
        }else{
            $(".home .defis div.round_tab").removeClass("active");
            $(this).addClass("active");
            $(".home .defis .affichage-defis .defi").hide();
            $(".home .defis ." + challengeType + " .defi").show();
        }

    });


// --------------  toastr options -------------- //

    /*toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "10000000",
        "hideDuration": "1000",
        "timeOut": "500000",
        "extendedTimeOut": "100000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }*/

// -------------- Confirmation suppression objet ----------- //
    $(".objects a[name='delete']").click(function(){
        return confirm('Etes-vous sûr de vouloir supprimer cet objet ?');
    });

});