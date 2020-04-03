<?php

namespace App\Http\Controllers\Upr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Devo aggiungere questo namespace per dirgli di usare il controller
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index()
    {
        return view('upr.flats.index');
    }
}
