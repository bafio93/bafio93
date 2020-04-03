<?php

namespace App\Http\Controllers\Upr;

use App\Flat;
use App\Sponsor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Braintree_Transaction;

class PaymentsController extends Controller
{

    public function index(Request $request)
    {
        // LOGICA DI VERIFICA UTENTE (vedi show() in FlatController per commenti):
        $logged_user = Auth::user()->id;
        // Trovo gli appartamenti:
        $id = $request["id"];
        $flat = Flat::findOrFail($id);
        // Effettuo valutazione:
        $flat_user = $flat->user->id;
        // Oltre a vedere che l'utente sia effettivamente quello connesso, valuto anche se l'offerta per quell'appartamento è scaduta o meno.
        if($logged_user == $flat_user) {
            $id = $request["id"];
            $sponsor_id = $request["sponsor_id"];
            // Aggiungo alcune informazioni alla pagina:
            $sponsor_hours = Sponsor::find($sponsor_id)->hours;
            $sponsor_price = Sponsor::find($sponsor_id)->price;
            $sponsor_title = $flat->title;
            // Questa è la data di sponsorizzazione (se esiste):
            $start_date = DB::table('flat_sponsor')->select("created_at")->where("flat_id", $id)->first();
            // Se non esiste data di sponsorizzazione, posso andare tranquillamente nella pagina di pagamento:
            if (!$start_date) {
                return view('upr.payment', ["id" => $id, "sponsor_id" => $sponsor_id, "sponsor_hours" => $sponsor_hours, "sponsor_title" => $sponsor_title, "sponsor_price" => $sponsor_price]);
            } else {
                // Se la sponsorizzazione dell'appartamento esiste ed è scaduta, posso andare al pagamento. Altrimenti torno alla index!
                $start_date = $start_date->created_at;
                // Differenza oraria:
                $hour_diff = now()->diffInHours($start_date);
                // Inizio della promozione attiva, seleziono prima l'id della sponsorizzazione attiva:
                $active_hours = DB::table('flat_sponsor')->select("sponsor_id")->where("flat_id", $id)->first()->sponsor_id;
                // Tramite l'id prendo il numero di ore di attività:
                $active_hours = Sponsor::find($active_hours)->hours;
                // Valuto se la differenza oraria tra now() e l'inizio della promozione:
                if ($hour_diff >= $sponsor_hours) {
                    // Mando i dati alla view:
                    return view('upr.payment', ["id" => $id, "sponsor_id" => $sponsor_id, "sponsor_hours" => $sponsor_hours, "sponsor_title" => $sponsor_title, "sponsor_price" => $sponsor_price]);
                } else {
                    // Ritorno all'index, non è ancora il momento di rinnovare!
                    return redirect()->route('upr.flats.index');
                }
            }
        } else {
            return redirect()->route('upr.flats.index');
        }
    }

    // Funzione per il salvataggio a DB della sponsorizzazione e per il pagamento:
    public function process(Request $request, $id, $sponsor_id)
    {
        // LOGICA DI VERIFICA UTENTE (vedi show() in FlatController per commenti):
        $logged_user = Auth::user()->id;
        // Trovo gli appartamenti:
        $flat = Flat::find($id);
        // Effettuo valutazione:
        $flat_user = $flat->user->id;
        if($logged_user == $flat_user) {
            $sponsor = Sponsor::find($sponsor_id);
            // Logica di Braintree:
            $payload = $request->input('payload', false);
            $nonce = $payload['nonce'];
            $status = Braintree_Transaction::sale([
                'amount' => $sponsor->price,
                'paymentMethodNonce' => $nonce,
                'options' => [
                    'submitForSettlement' => True
                ]
            ]);
            // Visto che posso rinnovare sponsorizzazioni, valutiamo se esiste già una colonna a DB: ne tengo solo una!
            $is_flat_sponsored = DB::table('flat_sponsor')->select("sponsor_id")->where("flat_id", $id)->get();
            // Questa è una collection, di cui posso valutare il count():
            if ($is_flat_sponsored->count()) {
                // Se avevo già degli sponsor, ripulisco il tutto.
                DB::table('flat_sponsor')->where('flat_id', $id)->delete();
            }
            // Salvo nel DB:
            $flat->sponsors()->sync($sponsor);
            $flat->save();
            // Mando risposta a Braintree:
            return response()->json($status);
        } else {
            return redirect()->route('upr.flats.index');
        }
    }
}
