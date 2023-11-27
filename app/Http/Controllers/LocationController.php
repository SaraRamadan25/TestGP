<?php

namespace App\Http\Controllers;

use App\Events\LocationUpdated;
use App\Traits\GeneralTrait;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    use GeneralTrait;

    public function positionStack(Request $request)
    {
        try {
            if (!$request->has('lat') || !$request->has('long')) {
                return $this->returnError(202, 'long and lat are required');
            }

            $client = new Client();
            $result = (string) $client->get(
                'http://api.positionstack.com/v1/reverse?access_key='
                . env('POSITION_STACK_KEY')
                . '&query=' . $request->lat
                . ',' . $request->long
            )->getBody();

            $json = json_decode($result, true);

            $country = $json['data'][0]['country'] ?? null;
            $continent = $json['data'][0]['continent'] ?? null;

            event(new LocationUpdated($request->lat, $request->long, $country, $continent));

            return $json;
        } catch (\Exception $e) {
            return $this->returnError(201, $e->getMessage());
        }
    }


    public function arcgis(Request $request)
    {
        try {
            if (!$request->has('lat') || !$request->has('long')) {
                return $this->returnError(202, 'long and lat are required');
            }

            $client = new Client();
            $result = (string) $client->get(
                'https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&featureTypes=&location='
                . $request->lat
                . ',' . $request->long
            )->getBody();

            $json = json_decode($result, true);

            event(new LocationUpdated($request->lat, $request->long));

            return $json;
        } catch (\Exception $e) {
            return $this->returnError(201, $e->getMessage());
        }
    }
}
