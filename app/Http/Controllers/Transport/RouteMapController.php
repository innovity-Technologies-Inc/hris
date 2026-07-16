<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transport\StoreRouteMapRequest;
use App\Http\Requests\Transport\UpdateRouteMapRequest;
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

    public function store(StoreRouteMapRequest $request)
    {
        try {
            RouteMap::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Route Map Created Successfully',
                'redirect' => route('transport.route_maps.index')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Route Map Create Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
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

    public function update(UpdateRouteMapRequest $request, $id)
    {
        try {
            $routeMap = RouteMap::findOrFail($id);
            $routeMap->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Route Map Updated Successfully',
                'redirect' => route('transport.route_maps.index')
            ], 200);
        } catch (\Exception $e) {
            Log::error('Route Map Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
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
