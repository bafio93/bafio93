<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'fa_icon'
    ];

    // Funzione che collega molti flat a molti service
    public function flats() {
        return $this->belongsToMany("App\Flat");
    }
}
