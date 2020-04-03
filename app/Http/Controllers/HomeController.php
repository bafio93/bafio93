<?php

namespace App\Http\Controllers;

use App\Flat;
use App\Sponsor;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {

        // Seleziono tutti gli appartamenti attivi
        $all_flats = Flat::where('active', 1)->orderBy('created_at', 'desc')->get();

        // Selezione tutti gli appartamenti che non sono mai stati sponsorizzati
        $flats_no_sponsor = DB::table('flats')
        ->leftJoin('flat_sponsor', 'flats.id', '=', 'flat_sponsor.flat_id')
        ->whereNull('flat_sponsor.flat_id')
        ->where('active', 1)
        ->orderBy('flats.created_at', 'desc')
        ->get();

        // Selezione tutti gli appartamenti con sponsorizzazione scaduta
        foreach ($all_flats as $flat) {
            $flat_sponsor = $flat->sponsors->toArray();

            foreach ($flat_sponsor as $sponsor) {

                $flat_sponsored_expired = DB::table('flats')
                ->join('flat_sponsor', 'flats.id', '=', 'flat_sponsor.flat_id')
                ->where([
                    ['flat_sponsor.created_at', '<', DB::raw('DATE_SUB(NOW(), INTERVAL '.$sponsor['hours'].' HOUR)')],
                    ['active', 1]])
                ->orderBy('flat_sponsor.created_at', 'desc')
                ->get();

            }
        }

        // Creiamo una collection che è l'unione delle due query fatte in precedenza che sarebbero tutti gli appartamenti mai sponsorizzati o con sponsorizzazione scaduta
        $flats_not_sponsor = $flats_no_sponsor->merge($flat_sponsored_expired);


        // Seleziono tutti gli appartamenti sponsorizzati attualmente
        foreach ($all_flats as $flat_due) {
            $flat_sponsor_due = $flat_due->sponsors->toArray();
            //dd($flat_sponsor);
            foreach ($flat_sponsor_due as $sponsor_due) {

                $flat_sponsored = DB::table('flats')
                ->join('flat_sponsor', 'flats.id', '=', 'flat_sponsor.flat_id')
                ->where([
                    ['flat_sponsor.created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$sponsor_due['hours'].' HOUR)')],
                    ['active', 1]])
                ->orderBy('flat_sponsor.created_at', 'desc')
                ->get();

            }
        }

        if(!empty($flat_sponsored)){
            return view('home', ['flats' => $flats_not_sponsor, 'flat_sponsored' => $flat_sponsored]);
        } else  {
            return view('home', ['flats' => $flats_not_sponsor]);
        }

    }

    public function detailsFlat(Request $request, $id)
    {
        $flat = Flat::find($id);

        // Memorizzo in una variabile tutti gli url visitati dall'utente memorizzati nella sessione
        $session_url_visited = session('clicked_url');

        // Controlliamo se nell'array degli url presi dalla sessione esiste il link della pagina di dettaglio che l'utente sta attualmente visitando in modo da non conteggiare di nuovo la visita dell'appartamento
        if (!in_array(url()->current(), session('clicked_url'))) {
            // Se è un utente ospite oppure un utente loggato senza appartamenti
            if(!Auth::user() OR (Auth::user() && Auth::user()->flats->count() == 0)) {
                // Incremento la visita di questo apparatmento
                $flat->increment('view');
            // Se è un utente loggato con appartamenti
            } elseif (Auth::user() && Auth::user()->flats->count() > 0) {
                // Cerco gli appartamenti appartenenti all'utente
                $user_flats  = Auth::user()->flats->toArray();
                // Ricavo lo user_id degli appartamenti dell'utente
                foreach ($user_flats as $user_flat) {
                    $user_id_flats = $user_flat['user_id'];
                }
                // Controllo se la casa appartiene all'utente loggato che ha appartamenti
                if($user_id_flats != $flat['user_id']) {
                    $flat->increment('view');
                }
            }
        }

        return view('details_flat', ['flat' => $flat]);
    }
}
