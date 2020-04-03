// Importazione riferimenti mappe Tom Tom, dei services e del plugin di Ricerca con autocompletamento
import tt from '@tomtom-international/web-sdk-maps';
import { services } from '@tomtom-international/web-sdk-services';
import SearchBox from '@tomtom-international/web-sdk-plugin-searchbox';

// per avere jQuery
var $ = require('jquery');

// per avere Moment.js
var moment = require('moment');

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// CommonJS
const Swal = require('sweetalert2');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });

// Variabile mappe per la Find
var map_find;
// Variabile check per le mappe senza risultati
var check =  true;

var href = window.location.href;
// Variabile che memorizza il placeholder della  searchbox per le mappe
var place = 'Ovunque';

if(href.indexOf('/en') > -1) {
    // Variabile che memorizza il placeholder in inglese della  searchbox per le mappe
    place = 'Everywhere';
}

// Options for the fuzzySearch service
var searchOptions = {
    key: 'oclyz52wVpi1LORZUp0OoIykHNa1tfMP',
    language: 'it-IT',
    limit: 5,
};

var searchBoxOptions = {
    minNumberOfCharacters: 0,
    searchOptions: searchOptions,
    placeholder: place,
    noResultsMessage: 'Nessun risultato trovato'
};

// creazione dell'oggetto searchBox generico
const ttSearchBox = new SearchBox(services, searchBoxOptions);

// Dichiarazioni di alcune variabili globali (coordinate, titolo, indirizzo e risulta della success di Ajax per creare la mappa nella pagina di ricerca)

var lon_marker;
var lat_marker;
var title_marker;
var address_marker;
var risultati_marker_home;
var risultati_marker_find;

$(document).ready(function(){

    // Imposto l'input della searchbox a required
    $('.tt-search-box-input').prop('required',true);

    // Variabili da passare a createMap
    var lonNumber = $('#lonNumber').val();
    var latNumber = $('#latNumber').val();
    var title = $('#title').text();
    var address = $('#address').text();

    // nella searchbox della edit valorizzo il campo col valore precedente
    $("#address-edit").find(".tt-search-box-input").val(address);
    $(".tt-search-box-input").attr('name', 'address');

    // Creo la mappa solo quando mi trovo all'interno della pagina di dettaglio dell'appartamento
    if(href.indexOf('/flats/details') > -1) {
        // Chiamo la funzione che mi crea la mappa nella pagina di dettaglio
        createMap(lonNumber, latNumber, title, address, check);
    }

    // Istruzioni per caricare risultati di ricerca dalla home nella pagina di ricerca
    var address_search = $('#searchFind').val();
    var address_edit = $('#address').val();
    $(".fuzzy-find").find(".tt-search-box-input").val(address_search);
    $(".fuzzy-edit").find(".tt-search-box-input").val(address_edit);


    // Chiamata Ajax con i dati della Searchbar della HomePage
    if(href.indexOf('/flats/find') > -1)
    {

        // if(!$('#latNumberFind').val() && $('#lonNumberFind').val()) {
        //     $('#latNumberFind').val('12.4829321');
        //     $('#lonNumberFind').val('41.8933203');
        // }

        var lat = $('#latNumberFind').val();
        var lon = $('#lonNumberFind').val();
        var distance = 20;
        console.log(distance);
        $.ajax({
            url: 'http://localhost:8000/api/flats',
            method: 'GET',

            data:  {
                'lat': lat,
                'lon': lon,
                'distance': distance
            },

            success: function(data) {
                    if (data.success) {

                        risultati_marker_home = data.result;
                        //console.log(risultati_marker);

                        //Chiamo la funzione che mi crea la mappa nella pagina di dettaglio
                        createMapSearch(risultati_marker_home);

                        //console.log(data.result);
                        $('#card_container').empty();

                        for (var i = 0; i < data.result.length; i++) {

                            var template_html = $('#card_template').html();

                            var template_function = Handlebars.compile(template_html);

                            var variables = {
                                'img_uri': data.result[i].img_uri,
                                'title': data.result[i].title,
                                'flat_details': data.result[i].id
                            }

                            var html = template_function(variables);

                            $('#card_container').append(html);
                        }
                    } else {
                        var lat = $('#latNumberFind').val();
                        console.log(lat);
                        var lon = $('#lonNumberFind').val();
                        console.log(lon);
                        var title = $('#title').text();
                        var address = $('searchFind').text();
                        var check = false;
                        createMap(lat, lon, title, address, check);
                        if(href.indexOf('/en/flats/find') > -1) {
                            $('#card_container').append('<h3>The search did not find any apartment!<h3>');
                        } else {
                            $('#card_container').append('<h3>La ricerca non ha trovato nessun appartamento!<h3>');
                        }
                    }

            }
        })
    }


//    Facciamo in modo che il bottone cerca nella pagina Find faccia uscire il popup di convalida
//   in questo form non c'è una submit, perciò adottiamo questo trucco
(function($){
    var isValid = null;
    var form = $('#form_find');
    var submitButton = form.find('button[type="submit"]')
    var findButton = submitButton.filter('[name="btn_find"]');

    //submit form behavior
    var submitForm = function(e){
        console.log('form submit');
        //prevent form from submitting valid or invalid
        e.preventDefault();
        //user clicked and the form was not valid
        if(isValid === false){
            isValid = null;
            return false;
        }
        //user pressed enter, process as if they clicked save instead
        findButton.trigger('click');
    };

    //override submit button behavior
    var submitClick = function(e){
        //Test form validitiy (HTML5) and store it in a global variable so both functions can use it
        isValid = form[0].checkValidity();
        if(false === isValid){
            //allow the browser's default submit event behavior
            return true;
        }
        //prevent default behavior
        e.preventDefault();
    };

    //override submit form event
    form.submit(submitForm);

    //override submit button click event
    submitButton.click(submitClick);
})(jQuery);

// Popup di conferma per la cancellazione di un appartamento
$(document).on('click', '#delete_flat', function (e) {
    // Traduzione del popup di conferma per la cancellazione Flat
    var sure = 'Sei sicuro?';
    var deleting = "L'appartamento verrà cancellato definitivamente!";
    var confirm = "Si";

    if(href.indexOf('/en/upr/flats') > -1) {
        // Variabile che memorizza il placeholder in inglese della  searchbox per le mappe
        sure = 'Are you sure?';
        deleting = 'The apartment will be permanently deleted!';
        confirm = 'Yes';
    }


    var id = $(this).data('id');
    var form =  $(this).closest("form");
    e.preventDefault();
    console.log(id);
    Swal.fire({
            title: sure,
            text: deleting,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirm,
            cancelButtonText: 'No',
        })
        .then((willDelete) => {
        if (willDelete.value) {
          form.submit();
         }
    });
});

    // Chiamata Ajax nella pagina Find con eventuali filtri di Ricerca
    $('#btn_find').click(function(event){

        $('#searchFind').val($('.tt-search-box-input').val());
        console.log($('#searchFind').val());

        if ($('.tt-search-box-input').val()) {

            var lat = $('#latNumberFind').val();
            var lon = $('#lonNumberFind').val();
            var distance = $('#km_radius').val();
            var rooms = $('#room_qty').val();
            var beds = $('#bed_qty').val();

            // Prendo tutte le checkbox dei Servizi
            var checkbox_value = "";
            var checkbox_count = 0;
            $("input[name=check_services]").each(function () {
                var ischecked = $(this).is(":checked");
                if (ischecked) {
                    checkbox_count++;
                    checkbox_value += $(this).val() + ",";
                }
            });

            // Tolgo l'ultima virgola nell' array delle checkbox
            var index = checkbox_value.lastIndexOf(",");
            var checkbox_selected = checkbox_value.substring(0, index) + checkbox_value.substring(index + 1);
            if (!checkbox_selected) {
                checkbox_selected = 'empty';
            }
            console.log(checkbox_selected);
            console.log(checkbox_count);
            console.log(lat);
            $.ajax({
                url: 'http://localhost:8000/api/flats',
                method: 'GET',
                data:  {
                    'lat': lat,
                    'lon': lon,
                    'distance': distance,
                    'rooms': rooms,
                    'beds': beds,
                    'services': checkbox_selected,
                    'checkbox_count': checkbox_count,
                },

                success: function(data) {
                        if (data.success) {

                            risultati_marker_find = data.result;
                            //console.log($('.tt-search-box-input').val());
                            console.log(risultati_marker_find);
                                //check = true;
                                //Chiamo la funzione che mi crea la mappa nella pagina di dettaglio
                                createMapSearch(risultati_marker_find);
                                console.log('Mappa creata con la seconda funzione');
                                console.log(map_find);
                            $('#card_container').empty();

                            for (var i = 0; i < data.result.length; i++) {

                                var template_html = $('#card_template').html();

                                var template_function = Handlebars.compile(template_html);

                                var variables = {
                                    'img_uri': data.result[i].img_uri,
                                    'title': data.result[i].title,
                                    'flat_details': data.result[i].id
                                }

                                var html = template_function(variables);

                                $('#card_container').append(html);
                            }
                        } else {

                            $('#card_container').empty();

                            var lat = $('#latNumberFind').val();
                            console.log(lat);
                            var lon = $('#lonNumberFind').val();
                            console.log(lon);
                            var title = $('#title').text();
                            var address = $('searchFind').text();
                            var check = false;
                            //createMap(lat, lon, title, address, check);

                            if($('.tt-search-box-input').val() != $('#searchFindMap').val()) {
                                var dati = [
                                      {
                                        lon: lon,
                                        lat: lat,
                                        title: title,
                                        address: address
                                      }
                                    ];
                                console.log(dati);
                                createMapSearch(dati);
                                console.log('Mappa creata con la prima funzione');
                                $('#searchFindMap').val($('.tt-search-box-input').val());
                                console.log($('#searchFindMap').val());
                            }

                            //if($('.tt-search-box-input').val() == $('#searchFindMap').val()) {
                                $(".mapboxgl-canvas-container").each(function(){
                                    $(this).find('.mapboxgl-marker').remove();
                                });
                            //}
                            if(href.indexOf('/en/flats/find') > -1) {
                                $('#card_container').append('<h3 class="text-center">The search did not find any apartment!<h3>');
                            } else {
                                $('#card_container').append('<h3 class="text-center">La ricerca non ha trovato nessun appartamento!<h3>');
                            }
                            // $(".mapboxgl-canvas-container").each(function(){
                            //     $(this).find('.mapboxgl-marker').remove();
                            // });

                            //Chiamo la funzione che mi crea la mappa nella pagina di dettaglio
                            //createMapSearch(risultati_marker_home);
                        }
                }
            })
        }

        });

            //-----FORM VALIDATION BOOTSTRAP-----------//
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                  // Fetch all the forms we want to apply custom Bootstrap validation styles to
                  var forms = document.getElementsByClassName('needs-validation');
                  // Loop over them and prevent submission
                  var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                      if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                      }
                      form.classList.add('was-validated');
                    }, false);
                  });
                }, false);
              })();

            // Funzione di validazione del nome e cognome in fase di registrazione
            function validation(parametro,valido,invalido){
                $(parametro).keyup(function(){
                    var value = ($(parametro).val());
                    // console.log(value);
                    if (value.length >= 3 && value.length <= 20) {
                        $(valido).show();
                        $(invalido).hide();
                        $(parametro).addClass('is-valid');
                        $(parametro).removeClass('is-invalid');
                        // $('.needs-validation').addClass('was-validated');
                    }else{
                        $(valido).hide();
                        $(invalido).show();
                        $(parametro).addClass('is-invalid');
                        $(parametro).removeClass('is-valid');
                    }
                    return parametro,valido,invalido;
                })

            }
            // Validazione Nome e cognome in fase di registrazione
            validation('#name','.name.valid-feedback','.name.invalid-feedback');
            validation('#surname','.surname.valid-feedback','.surname.invalid-feedback');


        // validation delle date in fase di registrazione

        $('#date_of_birth').keyup(function(){
          var date_of_birth = $('#date_of_birth').val();
          var dataUtente = moment(date_of_birth);
          // console.log(dataUtente);
          var date = moment().subtract(18, 'years');
          // console.log(date_of_birth);
          if (dataUtente < date) {
              // console.log('ok');
              $('.date.valid-feedback').show();
              $('.date.invalid-feedback').hide();
              $('#date_of_birth').addClass('is-valid');
              $('#date_of_birth').removeClass('is-invalid');
          }else{
              // console.log('no');
              $('.date.invalid-feedback').show();
              $('.date.valid-feedback').hide();
              $('#date_of_birth').addClass('is-invalid');
              $('#date_of_birth').removeClass('is-valid');
          }
      });
    // validazioni email
        function validationEmail(mail,valido,invalido,submit){
            $(mail).keyup(function(){
                var email = $(mail).val();
                var chioccia = (email.indexOf('@')+ 1);
                var point = (email.indexOf('.')+1);
                var point2 = (email.indexOf('.',point)+1);
                // console.log(chioccia);
                // console.log(point);
                // console.log(point2);
                if ( chioccia > point && point2 > chioccia) {
                    // console.log('ok');
                    $(submit).removeAttr("disabled");
                    $(valido).show();
                    $(invalido).hide();
                    $(mail).addClass('is-valid');
                    $(mail).removeClass('is-invalid');
                }else if(chioccia >= 1 && chioccia < point){
                    // console.log('ok');
                    $(submit).removeAttr("disabled");
                    $(valido).show();
                    $(invalido).hide();
                    $(mail).addClass('is-valid');
                    $(mail).removeClass('is-invalid');
                }else{
                    // console.log('no');
                    $(submit).attr("disabled",true);
                    $(invalido).show();
                    $(valido).hide();
                    $(mail).addClass('is-invalid');
                    $(mail).removeClass('is-valid');
                }

                return mail,valido,invalido;
            })
        };
        // Validazione mail in fase di registrazione e invio messaggio
        validationEmail('#email','.mail.valid-feedback','.mail.invalid-feedback','.invio');
        validationEmail('#msg_email','.msg_mail.valid-feedback','.msg_mail.invalid-feedback','.invio');

        // Funzione di validazione della lunghezza  messaggio nei details e lunghezza della mail > 0
        $('#text_msg').keyup(function(){
            var value = $('#text_msg').val();
            var email = $('#msg_email').val();
            // console.log(value);
            if (value.length >=10 && value.length <=255) {
                // $('.invio').removeAttr("disabled");
                $(".text_msg.valid-feedback").show();
                $(".text_msg.invalid-feedback").hide();
                $('#text_msg').addClass('is-valid');
                $('#text_msg').removeClass('is-invalid');
            }else{
                // $('.invio').add("disabled");
                $(".text_msg.valid-feedback").hide();
                $(".text_msg.invalid-feedback").show();
                $('#text_msg').addClass('is-invalid');
                $('#text_msg').removeClass('is-valid');
            }
        })
        // Attivo il tasto invio se la mail e il campo di testo del messaggio hanno classe valid
        $('.mess_box').keyup(function(){
            if ($('#msg_email').hasClass('is-valid') && $('#text_msg').hasClass('is-valid')) {
                $('.invio').removeAttr("disabled");

            }else{
                $('.invio').attr("disabled",true);
            }
        });


    //validation nella pagina del Create.blade della descrizione
        $('#title').keyup(function(){
            var titolo = $('#title').val();
            if (titolo.length >= 5) {
                $('.title.valid-feedback').show();
                $('.title.invalid-feedback').hide();
                $('#title').addClass('is-valid');
                $('#title').removeClass('is-invalid');
            }else{
                // console.log('no');
                $('.title.invalid-feedback').show();
                $('.title.valid-feedback').hide();
                $('#title').addClass('is-invalid');
                $('#title').removeClass('is-valid');
            }
        });

    //Funzione di validation nella pagina del Create.blade dei numeri di bagni,letti e camere
    function validationNumber(parametro,valido,invalido,){
        $(parametro).keyup(function(){
            var value = ($(parametro).val());
            // console.log(value);
            if (value >= 1 && value <= 50) {
                $(valido).show();
                $(invalido).hide();
                $(parametro).addClass('is-valid');
                $(parametro).removeClass('is-invalid');
                // $('.needs-validation').addClass('was-validated');
            }else{
                $(valido).hide();
                $(invalido).show();
                $(parametro).addClass('is-invalid');
                $(parametro).removeClass('is-valid');
            }

            return parametro,valido,invalido;
        })

    }
        //validation nella pagina del Create.blade dei numeri di bagni,letti e camere
        validationNumber('#create #room_qty','#create .room_qty.valid-feedback','#create .room_qty.invalid-feedback');
        validationNumber('#create #bed_qty','#create .bed_qnty.valid-feedback','#create .bed_qnty.invalid-feedback');
        validationNumber('#create #bath_qty','#create .bath_qty.valid-feedback','#create .bath_qty.invalid-feedback');
        validationNumber('#edit #room_qty','#edit .room_qty.valid-feedback','#edit .room_qty.invalid-feedback');
        validationNumber('#edit #bed_qty','#edit .bed_qnty.valid-feedback','#edit .bed_qnty.invalid-feedback');
        validationNumber('#edit #bath_qty','#edit .bath_qty.valid-feedback','#edit .bath_qty.invalid-feedback');

        //Funzione di validation nella pagina del Create.blade dei mq
        function validationMq(parametro,valido,invalido,){
            $(parametro).keyup(function(){
                var value = ($(parametro).val());
                // console.log(value);
                if (value >= 10 && value <= 500) {
                    $(valido).show();
                    $(invalido).hide();
                    $(parametro).addClass('is-valid');
                    $(parametro).removeClass('is-invalid');
                    // $('.needs-validation').addClass('was-validated');
                }else{
                    $(valido).hide();
                    $(invalido).show();
                    $(parametro).addClass('is-invalid');
                    $(parametro).removeClass('is-valid');
                }

                return parametro,valido,invalido;
            })

        }
            //validation nella pagina del Create.blade dei numeri dei Mq
            validationMq('#sq_meters','.sq_meters.valid-feedback','.sq_meters.invalid-feedback');


        // validazione grandezza immagine in create.blade
        $('#img_uri').bind('change', function() {
            var a=(this.files[0].size);
            if(a < 2000000) {
                // alert("L'immagine selezionata supera i 5MB!!!");
                $('#crea').removeAttr("disabled");
                $('.img_uri.invalid-tooltip').hide();
                $('.img_uri.valid-tooltip').show();
            }else{
                $('#crea').attr("disabled",true);
                $('.img_uri.valid-tooltip').hide();
                $('.img_uri.invalid-tooltip').show();
            };
        });
    //});

});

// searchbox per la pag create
const searchBoxCreate = ttSearchBox.getSearchBoxHTML();
$('.fuzzy-create').append(searchBoxCreate);

// searchbox per la pag home
const searchBoxHome = ttSearchBox.getSearchBoxHTML();
$('.fuzzy-home').append(searchBoxHome);

// searchbox per la pag edit
const searchBoxEdit = ttSearchBox.getSearchBoxHTML();
$('.fuzzy-edit').append(searchBoxEdit);

// searchbox per la pag edit
const searchBoxFind = ttSearchBox.getSearchBoxHTML();
$('.fuzzy-find').append(searchBoxFind);

// Evento che si verifica quando seleziono una voce dalla lista dell'autocompletamento nella searchbox
ttSearchBox.on('tomtom.searchbox.resultselected', handleResultSelection);
ttSearchBox.on('tomtom.searchbox.resultscleared', handleResultsCleared);
ttSearchBox.on('tomtom.searchbox.resultsfound', handleResultsFound);


function handleResultsCleared() {
    // Disattivo i bottoni di ricerca al click sulla x anche dopo aver selezionato una voce dall'autocmpletamento
    // if ($('.tt-search-box-input').val().length == 0) {
    //     $('#btn_home').attr('disabled', 'disabled');
    //     $('#btn_find').attr('disabled', 'disabled');
    // }
}

function handleResultsFound(event) {
            // Display fuzzySearch results if request was triggered by pressing enter
            //if (event.data.results.fuzzySearch && event.data.metadata.triggeredBy === 'submit') {
            if (event.data.results.fuzzySearch) {
                var results = event.data.results.fuzzySearch.results;
                console.log(results[0]);
                if (results.length === 0) {
                    //$('.tt-search-box-input').val('');
                    // $('#btn_home').attr('disabled', 'disabled');
                    // $('#btn_find').attr('disabled', 'disabled');
                }

                    var longitudine = results[0].position.lng;
                    var latitudine = results[0].position.lat;

                    // coordinate per la pagina details
                    $('#lat').val(latitudine);
                    $('#lon').val(longitudine);

                    // coordinate per la home
                    $('#latNumberHome').val(latitudine);
                    $('#lonNumberHome').val(longitudine);

                    // coordinate per la find
                    $('#latNumberFind').val(latitudine);
                    $('#lonNumberFind').val(longitudine);

                    // indirizzo inserito nella searchbar in home (lo assegno al campo nascosto dell'indirizzo in home)
                    $('#searchHome').val($('.tt-search-box-input').val());
            }
        }

function handleResultSelection(event) {
    if (isFuzzySearchResult(event)) {

        var result = event.data.result;
        console.log(result);
        // Memorizzo le coordinate lon e lat
        var longitudine = result.position.lng;
        var latitudine = result.position.lat;

        // coordinate per la pagina details
        $('#lat').val(latitudine);
        $('#lon').val(longitudine);

        // coordinate per la home
        $('#latNumberHome').val(latitudine);
        $('#lonNumberHome').val(longitudine);

        // coordinate per la find
        $('#latNumberFind').val(latitudine);
        $('#lonNumberFind').val(longitudine);

        // indirizzo inserito nella searchbar in home (lo assegno al campo nascosto dell'indirizzo in home)
        $('#searchHome').val($('.tt-search-box-input').val());

    }
}

function isFuzzySearchResult(event) {
    return !('matches' in event.data.result);
}


function createMap(longitudine, latitudine, title, address, check) {
    //console.log(risultati_marker);

    //var roundLatLng = Formatters.roundLatLng;
    var center = [latitudine, longitudine];

    map_find = tt.map({
        key: 'oclyz52wVpi1LORZUp0OoIykHNa1tfMP',
        container: 'map',
        center: center,
        zoom: 8,
        style: 'tomtom://vector/1/basic-main',
        dragPan: !isMobileOrTablet()
    });

    map_find.addControl(new tt.FullscreenControl());
    map_find.addControl(new tt.NavigationControl());

    // Se la mappa  è costruita con appartamenti crea il marker e il popup
    if(check) {
        //Creazione del marker all'indirizzo dell'Appartamento e del popup sovrastante
        var popup = new tt.Popup({
                 offset: 35
        });
        var marker = new tt.Marker({
        }).setLngLat(center).addTo(map_find);

        popup.setHTML("<strong>" + title + "</strong>" + "<br>" + address + "<br>");
        marker.setPopup(popup);
        //marker.togglePopup();
    }
}

function createMapSearch(risultati) {

    var center = [risultati[0].lon, risultati[0].lat];
    console.log(center);

    if($('.tt-search-box-input').val() != $('#searchFindMap').val()) {

        // Memorizzo l'indirizzo attuale nel campo nascosto
        console.log($('#searchFindMap').val());
        $('#searchFindMap').val($('.tt-search-box-input').val());

        map_find = tt.map({
            key: 'oclyz52wVpi1LORZUp0OoIykHNa1tfMP',
            container: 'map',
            center: center,
            zoom: 8,
            style: 'tomtom://vector/1/basic-main',
            dragPan: !isMobileOrTablet()
        });

        map_find.addControl(new tt.FullscreenControl());
        map_find.addControl(new tt.NavigationControl());

        for (var i = 0; i < risultati.length; i++) {
            var popup = new tt.Popup({
                     offset: 35
            });
            //Creazione del marker all'indirizzo dell'Appartamento
            var marker = new tt.Marker({
            }).setLngLat([risultati[i].lon, risultati[i].lat]).addTo(map_find);

            popup.setHTML("<strong>" + risultati[i].title + "</strong>" + "<br>" + risultati[i].address);
            marker.setPopup(popup);
            //marker.togglePopup();
        }
    } else {

        $(".mapboxgl-canvas-container").each(function(){
            $(this).find('.mapboxgl-marker').remove();
        });
        console.log('Sto creando i popup nella seconda create');
        for (var i = 0; i < risultati.length; i++) {
            var popup = new tt.Popup({
                     offset: 35
            });
            console.log('SOno qui!');
            //Creazione del marker all'indirizzo dell'Appartamento
            var marker = new tt.Marker({
            }).setLngLat([risultati[i].lon, risultati[i].lat]).addTo(map_find);
            console.log(marker);
            popup.setHTML("<strong>" + risultati[i].title + "</strong>" + "<br>" + risultati[i].address);
            marker.setPopup(popup);
            //marker.togglePopup();
        }
    }
}
