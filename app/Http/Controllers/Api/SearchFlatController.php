<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Flat;
use App\Service;
use Illuminate\Support\Facades\DB;

class SearchFlatController extends Controller
{
    public function index() {

        $lat = $_GET['lat'];
        $lon = $_GET['lon'];
        $distance = $_GET['distance'];


        if (array_key_exists("rooms", $_GET) AND array_key_exists("beds", $_GET)) {
            $rooms = $_GET['rooms'];
            $beds = $_GET['beds'];

            if($_GET['services'] != 'empty') {
                $services = $_GET['services'];

                $checkbox_count = $_GET['checkbox_count'];

                $services_array = explode(',', $services);

                $flats = DB::table('flats')
                ->join('flat_service', 'flat_service.flat_id', '=', 'flats.id')
                ->selectRaw('id, title, address, lat, lon, img_uri, ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) *
                               cos( radians( lon ) - radians(?) ) + sin( radians(?) ) *
                        sin( radians( lat ) ) ) ) AS distance, COUNT(flats.id) AS number_services', [$lat, $lon, $lat])
                ->whereIn('service_id', $services_array)
                ->where([
                            ['active', 1],
                            ['room_qty', '>=', $rooms],
                            ['bed_qty', '>=', $beds]
                        ])
                ->groupBy('flats.id')
                ->havingRaw('number_services = ? AND distance <= ?', [$checkbox_count, $distance])
                ->orderBy('distance')
                ->get();
            } else {

                $flats = DB::select( DB::raw("
                    SELECT *, ( 6371 * acos( cos( radians('$lat') ) * cos( radians( lat ) ) *
                    cos( radians( lon ) - radians('$lon') ) + sin( radians('$lat') ) *
                    sin( radians( lat ) ) ) ) AS distance FROM flats HAVING
                    distance < '$distance' AND room_qty >= '$rooms' AND bed_qty >= '$beds' ORDER BY distance LIMIT 0 , 20
                "));
            }
        } else {

            $flats = DB::select( DB::raw("
                SELECT *, ( 6371 * acos( cos( radians('$lat') ) * cos( radians( lat ) ) *
                cos( radians( lon ) - radians('$lon') ) + sin( radians('$lat') ) *
                sin( radians( lat ) ) ) ) AS distance FROM flats HAVING
                distance < '$distance' ORDER BY distance LIMIT 0 , 20
            "));
        }

        if (count($flats) > 0) {
            return response()->json(
                [
                    'success' => true,
                    'result' => $flats
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'result' => 'La ricerca non ha prodotti risultati!'
                ]
            );
        }
    }
}
