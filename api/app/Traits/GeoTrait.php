<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait GeoTrait
{
    public function getLatLong($address) {
        // url encode the address
        $address = urlencode($address);

        // google map geocode api url
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyB6NGRFPQpJYG6kaBi7dYHIUanoARLEW2A";

        try {
            // decode the json
            $resp = json_decode(file_get_contents($url), true);
            // response status will be 'OK', if able to geocode given address
            if ($resp['status'] == 'OK') {

                // get the important data
                $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
                $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";

                // verify if data is complete
                if ($lati && $longi) {
                    // put the data in the array
                    $data_arr = array();

                    array_push(
                        $data_arr,
                        $lati,
                        $longi,
                    );
                    return $data_arr;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\ErrorException $th) {
            Log::info($th);
            return false;
        }
       
    }
}


