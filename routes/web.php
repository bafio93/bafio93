<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    Route::get('/', 'HomeController@index')->name('home');

    // Genera tutte le rotte per la gestione dell'autenticazione
    Auth::routes();

    //Rotta pagina ricerca appartamenti con filtri
    Route::post('/flats/find', 'FlatController@find')->name('flat.find');

    // Rotta pagina di dettaglio di un Appartamento
    Route::get('/flats/details/{id}', 'HomeController@detailsFlat')->name('flat.details');

    // Rotta per invio messaggio
    Route::post('/flats/send/message', 'MessageController@sendMail')->name('send.mail');

    // Rotta per visualizzare i messaggi
    Route::resource("upr/flats/messages" , "MessageController");

    // Rotta per l'about_us:
    Route::get('/about_us', function () {
        return view('about_us');
    })->name("about_us");

    // Specifichiamo un gruppo di route che condividono una serie di comandi,  come per esempio il fatto che possono essere visualizzati solo se si è loggati
    Route::middleware('auth')->prefix('upr')->namespace('Upr')->name('upr.')->group(function() {
        Route::get('/', 'HomeController@index')->name('home');
        Route::resource("/flats" , "FlatController");
        // Rotta per la sponsorizzazione:
        Route::get('flats/{flat}/sponsor', 'FlatController@sponsor')->name('flats.sponsor');
        // Rotta per gestire il form della sponsorizzazione:
        Route::post('/flats/submit/sponsor', 'FlatController@submitSponsor')->name('flats.submit');
        // Rotta per gestire i pagamenti:
        Route::get('/payment', 'PaymentsController@index')->name('payment.index');
        Route::get('/payment/process/flat/{id}/sponsor/{sponsor_id}', 'PaymentsController@process')->name('payment.process');
    });
});
