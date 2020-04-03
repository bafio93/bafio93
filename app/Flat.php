<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    protected $fillable = [
        'title', 'room_qty', 'bed_qty', 'bath_qty', 'sq_meters', 'address', 'lat', 'lon', "active"
    ];

    // Funzione che collega 1 user a molti flat
    public function user() {
        return $this->belongsTo("App\User");
    }

    // Funzione che collega 1 flat a molti message
    public function messages() {
        return $this->hasMany("App\Message");
    }

    // Funzione che collega molti flat a molti service
    public function services() {
        return $this->belongsToMany("App\Service");
    }

    // Funzione che collega molti flat a molti sponsor
    public function sponsors() {
        return $this->belongsToMany("App\Sponsor")->withTimestamps();
    }


}
