<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLeadFromUser extends Mailable
{
    use Queueable, SerializesModels;
    // Dichiarandole public la posso usare in new_message.blade
    public $message_to_owner;
    public $name_owner_msg;
    public $email_owner_msg;
    public $name_flat_msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $email_owner, $name_owner,  $name_flat)
    {
        $this->message_to_owner = $message;
        $this->name_owner_msg = $name_owner;
        $this->email_owner_msg = $email_owner;
        $this->name_flat_msg = $name_flat;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    // Funzione che viene chiamata in automatico quando voglio inviare la mail
    public function build()
    {
        // la view è l'email che viene inviata
        return $this->from($this->message_to_owner->msg_email)->subject('Richiesta informazioni: '.$this->name_flat_msg)->view('mail.new_message');
    }
}
