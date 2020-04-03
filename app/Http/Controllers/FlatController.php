<?php

namespace App\Http\Controllers;

use Session;
use App\Flat;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FlatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function find(Request $request)
    {
        $lat = $request['lat'];
        $lon = $request['lon'];
        $address = $request['address_home'];
        $title = $request['title'];
        $services = Service::all();

        return view('find_flat', [
            'lat' => $lat,
            "lon" => $lon,
            'address'=> $address,
            'title' => $title,
            'services' => $services,
        ]);
    }
    //
    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }
    //
    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }
    //
    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Flat  $flat
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Flat $flat)
    // {
    //     //
    // }
    //
    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Flat  $flat
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Flat $flat)
    // {
    //     //
    // }
    //
    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Flat  $flat
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Flat $flat)
    // {
    //     //
    // }
    //
    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Flat  $flat
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Flat $flat)
    // {
    //     //
    // }
}
