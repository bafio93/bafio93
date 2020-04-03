<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'date_of_birth', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Funzione che collega 1 user a molti flat
    public function flats() {
        return $this->hasMany("App\Flat");
    }
    // Funzione che mi consente di arrivare da User a Messaggi attraverso le rispettive relazioni
    public function messages()
    {
        return $this->hasManyThrough('App\Message', 'App\Flat');
    }
}
