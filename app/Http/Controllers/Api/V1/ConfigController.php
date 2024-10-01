<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Zone;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\CentralLogics\Helpers;
use Grimzy\LaravelMysqlSpatial\Types\Point;


class ConfigController extends Controller
{
        public function geocode_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count()>0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
       
        $response = Http::get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $request->lat,
            'lon' => $request->lng,
            'format' => 'json'
        ]);
        return $response->json();
        
    }
    public function get_zone(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }
    
            $point = new Point($request->lat, $request->lng);
            $zones = Zone::contains('coordinates', $point)->latest()->get();
    
            if ($zones->isEmpty()) {
                return response()->json(['message' => trans('messages.service_not_available_in_this_area_now')], 404);
            }
    
            foreach ($zones as $zone) {
                if ($zone->status) {
                    return response()->json(['zone_id' => $zone->id], 200);
                }
            }
    
            return response()->json(['message' => trans('messages.we_are_temporarily_unavailable_in_this_area')], 403);
    
        } catch (\Exception $e) {
            // Log the exception message
          
    
            // Return a generic error response
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
    
}
