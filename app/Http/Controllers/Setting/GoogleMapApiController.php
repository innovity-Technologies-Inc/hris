<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\GoogleMapApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleMapApiController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Google Map API';
        $section = 'Settings';
        $sub_section = 'Google Map API';
        $apiKey = GoogleMapApi::first();
        return view('setting.google_map_api', compact('title', 'section', 'sub_section', 'apiKey'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'google_maps_api_key' => 'required|string|max:500',
            'google_maps_radius' => 'required|integer|min:1',
        ], [
            'google_maps_api_key.required' => 'Please Enter Google Maps API Key',
            'google_maps_api_key.string' => 'API Key Must Be String',
            'google_maps_api_key.max' => 'API Key Must Be Less Than 500 Characters',
            'google_maps_radius.required' => 'Please Enter Covering Radius',
            'google_maps_radius.integer' => 'Covering Radius Must Be an Integer',
            'google_maps_radius.min' => 'Covering Radius Must Be at Least 1 Meter',
        ]);

        try {
            $apiKey = GoogleMapApi::first();

            if ($apiKey) {
                $apiKey->update([
                    'google_maps_api_key' => $request->google_maps_api_key,
                    'google_maps_radius' => $request->google_maps_radius,
                ]);
            } else {
                GoogleMapApi::create([
                    'google_maps_api_key' => $request->google_maps_api_key,
                    'google_maps_radius' => $request->google_maps_radius,
                ]);
            }
            cache()->forget('google_maps_api_key');
            cache()->forget('google_maps_radius');

            return response()->json([
                'success' => true,
                'message' => 'Google Maps Settings Saved Successfully'
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
