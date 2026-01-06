<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiKeyController extends Controller
{
    public function index(Request $request){
        $title = 'API Keys';
        $section = 'Settings';
        $sub_section = 'API Keys';
        $apiKey = ApiKey::first();
        return view('settings.api_keys', compact('title', 'section', 'sub_section', 'apiKey'));
    }

    public function save(Request $request){
        $request->validate([
            'google_maps_api_key' => 'required|string|max:500',
        ], [
            'google_maps_api_key.required' => 'Please Enter Google Maps API Key',
            'google_maps_api_key.string' => 'API Key Must Be String',
            'google_maps_api_key.max' => 'API Key Must Be Less Than 500 Characters',
        ]);

        try {
            $apiKey = ApiKey::first();

            if ($apiKey) {
                // Update existing record
                $apiKey->update([
                    'google_maps_api_key' => $request->google_maps_api_key,
                ]);
            } else {
                // Create new record (only once)
                ApiKey::create([
                    'google_maps_api_key' => $request->google_maps_api_key,
                ]);
            }
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'API Key Saved Successfully',
            'alert-type' => 'success'
        ]);
    }
}
