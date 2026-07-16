<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Models\Transport\RouteMap;
use App\HelperClass;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RouteMapController extends Controller
{
    public function index(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Route Maps';
        $section = 'Transport';
        $sub_section = 'Route Maps';

        $query = RouteMap::query();
        $searchableColumns = ['route_name', 'start_point', 'end_point'];
        $keyword = $request->input('keyword');
        $filters = [];

        $routeMaps = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->latest()->paginate(10);

        if ($request->ajax()) {
            return view('transport.route_map.search_results', compact('routeMaps'))->render();
        }

        return view('transport.route_map.index', compact('title', 'section', 'sub_section', 'routeMaps'));
    }

    public function create()
    {
        $title = 'Add Route Map';
        $section = 'Route Maps';
        $section_url = route('transport.route_maps.index');
        $sub_section = 'Add';

        return view('transport.route_map.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_name' => 'required|string|max:255',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'via_points' => 'nullable|string|max:1000',
            'route_details' => 'nullable|string|max:1000',
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            RouteMap::create($request->all());

            return redirect()->route('transport.route_maps.index')->with([
                'message' => 'Route Map Created Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Route Map Create Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }
    }

    public function edit($id)
    {
        $title = 'Edit Route Map';
        $section = 'Route Maps';
        $section_url = route('transport.route_maps.index');
        $sub_section = 'Edit';

        $routeMap = RouteMap::findOrFail($id);

        return view('transport.route_map.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'routeMap'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'route_name' => 'required|string|max:255',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'via_points' => 'nullable|string|max:1000',
            'route_details' => 'nullable|string|max:1000',
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            $routeMap = RouteMap::findOrFail($id);
            $routeMap->update($request->all());

            return redirect()->route('transport.route_maps.index')->with([
                'message' => 'Route Map Updated Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Route Map Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $routeMap = RouteMap::findOrFail($id);

            // Check if route map is associated with any transports
            if ($routeMap->employeeTransports()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete Route Map because it is associated with active Employee Transports.'
                ], 400);
            }

            $routeMap->delete();

            return response()->json([
                'success' => true,
                'message' => 'Route Map Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Route Map Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }
}
