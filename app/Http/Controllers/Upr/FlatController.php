<?php

namespace App\Http\Controllers\Upr;
use App\Flat;
use App\Message;
use App\Service;
use App\Sponsor;
use App\Http\Controllers\Controller; // Devo aggiungere questo namespace per dirgli di usare il controller
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FlatController extends Controller
{

    public function index()
    {
        // Seleziono tutti i Flat dell'utente:
        $utente = Auth::user();
        $flats = $utente->flats()->get();
        // Passo a index delle informazioni riguardo alla sponsorizzazione dell'appartamento:
        $flat_sponsored = DB::table('flats')
        ->join('flat_sponsor', 'flats.id', '=', 'flat_sponsor.flat_id')
        ->join("sponsors", "flat_sponsor.sponsor_id", "=", "sponsors.id")
        ->where("flats.user_id", $utente["id"])
        ->select("flat_id","sponsor_id","hours","flat_sponsor.created_at")
        ->get();
        // Con questo passaggio, customizzo la key della collection!
        $flat_sponsored = $flat_sponsored->keyBy("flat_id")->toArray();
        // Questo restituisce un array di oggetti.
        return view("upr.flats.index", ["flats" => $flats, "flat_sponsored" => $flat_sponsored]);

        // Se un appartamento di questi è già sponsorizzato, non mostro bottone!
        // dd($flat_sponsored->count());
    }

    public function create()
    {
        // Consento la creazione di un nuovo flat:
        // Passo anche tutti i servizi:
        $servizi = Service::all();
        return view("upr.flats.create", compact("servizi"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5|max:255',
            'room_qty' => 'required|numeric|min:1|max:50',
            'bed_qty' => 'required|numeric|min:1|max:50',
            'bath_qty' => 'required|numeric|min:1|max:50',
            'sq_meters' => 'required|numeric|min:10|max:500',
            'address' => 'required',
            'active' => 'required|boolean',
            'img_uri' => 'required|image|max:2000',
        ]);

        $data = $request->all();
        // creo un nuovo oggetto Flat da valorizzare
        $flat = new Flat();
        // imposto il campo dell'id user
        $flat->user_id = Auth::user()->id;
        // imposto tutti i campi definiti come fillable nel model Flat
        $flat->fill($data);
        // se l'immagine è stata caricata
        if(!empty($data['img_uri'])) {
            // recupero l'oggeto del file upload
            $uploaded_file = $data['img_uri'];
            // salvo il file nel mio spazio di storage e recupero il suo percorso
            $file_path = Storage::put('images', $uploaded_file);
            // imposto il percorso nel db
            $flat->img_uri = $file_path;
        }
        // salvo l'oggetto
        $flat->push();
        // Creo un vettore da active in poi(serve solo per i servizi):
        $service_array_uncut = array_slice($data,10);
        // Questo passaggio serve per eliminare l'image url dall'array di servizi:
        $service_array_cut = array_slice(array_reverse($service_array_uncut),1);
        // Prendo questo vettore e lo inserisco nella tabella pivot appropriata (DA AGGIUNGERE I TIMESTAMPS):
        $flat->services()->attach($service_array_cut);
        // torno alla view index
        return redirect()->route("upr.flats.index");

    }

    public function show($id)
    {
        // Ritrovo l'utente loggato
        $logged_user = Auth::user()->id;

        // Visualizzo la pagina di dettaglio del singolo appartamento:
        $flat = Flat::findOrFail($id);

        // Ritrovo l'utente propietario dell'appartamento
        $flat_user = $flat->user->id;
        // Controllo se l'utente loggato è uguale all'utente dell'appartamento (evito così che chiunque mettendo un id possa vedere i dettagli di un altro appartamento non suo)
        if($logged_user == $flat_user) {
            // Cerco nella tabella flat_service tutti i servizi offerti dal mio appartamento:
            $service_on_flat = DB::table("services")
            ->join("flat_service", "services.id", "=", "flat_service.service_id")
            ->where("flat_service.flat_id",$id)
            ->get();
            // Ottengo in uscita una collection, la preparo come un array:
            $service_array = [];
            foreach ($service_on_flat as $single) {
                array_push($service_array,$single->name);
            }
            return view("upr.flats.show", ["flat" => $flat, "service" => $service_array]);
        } else {
            return redirect()->back();
        }

    }

    public function edit(Flat $flat)
    {
        // LOGICA DI VERIFICA UTENTE (vedi show() per commenti):
        $logged_user = Auth::user()->id;
        $flat_user = $flat->user->id;
        if($logged_user == $flat_user) {
            // Visualizzo la pagina di modifica del singolo appartamento:
            $servizi = Service::all();
            // Cerco nella tabella flat_service tutti i servizi offerti dal mio appartamento:
            $servizi_su_appartamento = DB::table("services")
            ->join("flat_service", "services.id", "=", "flat_service.service_id")
            ->where("flat_service.flat_id",$flat->id)
            ->get();
            // Ottengo in uscita una collection, la preparo come un array:
            $servizi_su_appartamento_array = [];
            foreach ($servizi_su_appartamento as $single) {
                array_push($servizi_su_appartamento_array,$single->name);
            }
            // Passo alla mia view tutti gli array:
            return view("upr.flats.edit", ["flat" => $flat, "servizi" => $servizi, "servizi_su_appartamento_array" => $servizi_su_appartamento_array]);
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request, Flat $flat)
    {
        $request->validate([
            'title' => 'required|min:10|max:255',
            'room_qty' => 'required|numeric|min:1|max:200',
            'bed_qty' => 'required|numeric|min:1|max:50',
            'bath_qty' => 'required|numeric|min:1|max:50',
            'sq_meters' => 'required|numeric|max:500',
            //'address' => 'required|min:10',
            'lat' => '',
            'lon' => '',
            'active' => 'required|boolean',
            'img_uri' => 'image|max:2000',
        ]);
        // recupero i dati dal form

        $form_data = $request->all();
        // verifico se è stato caricato una nuova immagine
        if(!empty($form_data['img_uri'])) {
            // se già c'era un'immagine mi recupero il percorso e elimino il file dallo storage
            if (!empty($flat['img_uri'])) {
                $img_to_delete = $flat['img_uri'];
                Storage::delete($img_to_delete);
            }
            // recupero l'oggeto del nuovo file upload
            $uploaded_file = $form_data['img_uri'];
            // salvo il file nel mio spazio di storage e recupero il suo percorso
            $file_path = Storage::put('images', $uploaded_file);
            // imposto il percorso nel db
            $flat->img_uri = $file_path;
        }
        // Apporto le modifiche al flat nel DB:
        $flat->update($form_data);
        // Creo un vettore da active in poi(serve solo per i servizi):
        $index_active = array_search("active",array_keys($form_data));
        $service_array_edit = array_slice($form_data,($index_active+1));
        // Se in questo array ho img_uri, andrà eliminato.
        if (!empty($form_data["img_uri"])) {
            // Visto che img_uri sarà l'ultimo elemento dell'array, dovrò eliminarlo così:
            $service_array_edit = array_slice(array_reverse($service_array_edit),1);
        }
        // Prima elimino le righe della tabella flat_service che ci interessano:
        DB::table('flat_service')->where('flat_id', $flat["id"])->delete();
        // Poi aggiungo i servizi inseriti:
        $flat->services()->attach($service_array_edit);
        // torno alla view index
        return redirect()->route('upr.flats.index');
    }

    public function destroy(Request $request, Flat $flat, Message $message)
    {
        // Memorizzo una sessione per il messaggio di conferma cancellazione appartamento
        $request->session()->flash('status', 'ok');
        // Se l'appartamento ha dei messaggi, bisogna prima cancellarli:
        $messages_on_flat = Message::where("flat_id", $flat->id)->get();
        if ($messages_on_flat->count()) {
            Message::where("flat_id", $flat->id)->delete();
        }
        // se l'appartamento ha l'immagine mi recupero il percorso e elimino il file dallo storage
        if (!empty($flat['img_uri'])) {
            $img_to_delete = $flat['img_uri'];
            Storage::delete($img_to_delete);
        }
        $flat->delete();
        return redirect()->route('upr.flats.index');
        //return back();
    }

    // Funzione per andare alla pagina di sponsorizzazione del singolo appartamento!
    public function sponsor(Flat $flat)
    {
        // LOGICA DI VERIFICA UTENTE (vedi show() per commenti):
        $logged_user = Auth::user()->id;
        $flat_user = $flat->user->id;
        if($logged_user == $flat_user) {
            $sponsor = Sponsor::all();
            // Per evitare che l'utente acceda direttamente alla pagina di sponsorizzazione tramite la scrittura diretta nell'url, controllo se esistono già delle sponsorizzazioni (VALIDE, non scadute) presenti nel DB per questo appartamento:
            // Prendo l'id della sponsorizzazione, valuto se esiste:
            $is_flat_sponsored = DB::table('flat_sponsor')->select("sponsor_id")->where("flat_id",$flat->id)->get();
            if (!$is_flat_sponsored->count()) {
                return view('upr.flats.sponsor', ["flat" => $flat, "sponsor" => $sponsor]);
            } else {
                // Ottengo una collection di un solo elemento, quindi:
                $is_flat_sponsored = $is_flat_sponsored->first()->sponsor_id;
                // Se ho già delle sponsorizzazioni attive, devo attivarne altre solo se sono scadute.
                // Controllo le ore per la sponsorizzazione corrente:
                $sponsor_hours = ($sponsor->where("id",$is_flat_sponsored))->first()->hours;
                // Prendo la data in cui è stata creata la sponsorizzazione:
                $start_date = DB::table('flat_sponsor')->select("created_at")->where("flat_id",$flat->id)->get();
                // Ottengo una collection di un elemento, quindi:
                $start_date = $start_date->first()->created_at;
                // Prendo la differenza oraria:
                $hour_diff = now()->diffInHours($start_date);
                // ora valuto: se la differenza è maggiore delle ore di sponsorizzazione, significa che è scaduta. Quindi faccio entrare nella schermata di sponsorizzazione:
                if ( $hour_diff >= $sponsor_hours ) {
                    // SCADUTA:
                    return view('upr.flats.sponsor', ["flat" => $flat, "sponsor" => $sponsor]);
                } else {
                    // ATTIVA:
                    return redirect()->route('upr.flats.index');
                }
            }
        } else {
            return redirect()->back();
        }
    }
    // Funzione legate al pagamento spostate nel PaymentsController.
}
