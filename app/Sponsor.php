<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'hours', 'price'
    ];

    // Funzione che collega molti sponsor a molti flat
    public function flats() {
        return $this->belongsToMany("App\Flat");
    }
}
