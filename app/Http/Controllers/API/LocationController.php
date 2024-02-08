<?php

namespace App\Http\Controllers\API;

use App\Events\LocationUpdated;
use App\Http\Controllers\Controller;
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
                return $this->returnError(202, 'lat and long are required');
            }

            $client = new Client();

            $result = $client->get('http://api.positionstack.com/v1/reverse', [
                'query' => [
                    'access_key' => env('POSITION_STACK_KEY'),
                    'query' => $request->lat . ',' . $request->long,
                ],
            ]);

            $json = json_decode($result->getBody(), true);

            $country = $json['data'][0]['country'] ?? null;
            $continent = $json['data'][0]['continent'] ?? null;

            $mapUrl = "https://www.google.com/maps/search/?api=1&query={$request->lat},{$request->long}";

            event(new LocationUpdated($request->lat, $request->long, $country, $continent));

            $json['map_url'] = $mapUrl;

            return $json;
        } catch (\Exception $e) {
            return $this->returnError(201, $e->getMessage());
        }
    }

}
