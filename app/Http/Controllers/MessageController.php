<?php

namespace App\Http\Controllers;

use App\Message;
use App\Flat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewLeadFromUser;

class MessageController extends Controller
{
    public function __construct() {
        // Indico che solo la funzione index deve avere l'autenticazione
        $this->middleware('auth')->only('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Seleziono l'utente corrente
        $user = Auth::user();
        // Chiamo la funzione che mi restituisce i messaggi associati agli appartamenti dell'utente corrente
        $messages = $user->messages()
        ->orderBy('messages.created_at', 'desc')
        ->get();
        //dd($messages);

        return view('upr.flats.message', ['messages'=> $messages]);
    }

    public function sendMail(Request $request)
    {

        // Validation per l'invio della mail al proprietario di app
        $validator = $request->validate([
            'msg_email' => 'required|string|email|max:100',
            'text_msg' => 'required|min:10|max:255',
        ]);
        // Memorizzo una sessione per il messaggio di conferma invio email
        $request->session()->flash('status', 'ok');

        $data_form_message = $request->all();

        $message = new Message();
        $message->fill($data_form_message);
        $message->save();

        // Memorizzo l'email e il nome del proprietario dell'appartamento visitato
        $email_owner = $data_form_message['email_owner'];
        $name_owner = $data_form_message['name_owner'];
        $name_flat = $data_form_message['flat_title'];

        // Invio email al propietario dell'Appartamento
        Mail::to($email_owner)->send(new NewLeadFromUser($message, $email_owner, $name_owner, $name_flat));

        return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
