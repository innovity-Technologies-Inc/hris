<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

use App\Models\Company\GazetteLocation;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GazetteLocationsController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch){
        $title = 'Gazette Location';
        $section = 'Company Setup';
        $sub_section = 'Gazette Location';
        $query = GazetteLocation::query();
        $searchTerm = $request->get('keyword');
        $searchableFields = ['name'];
        $gazette_locations = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company.gazette_location.search_results', compact('gazette_locations'))->render();
        }
        return view('company.gazette_location.index', compact('title', 'section', 'sub_section', 'gazette_locations'));
    }

    public function create(){
        $title = 'Add Gazette Location';
        $section = 'Gazette Location';
        $section_url = route('gazette_locations.index');
        $sub_section = 'Add';
        return view('company.gazette_location.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
        ]);

        try{
            Log::info('Creating GazetteLocation');
            GazetteLocation::create($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Gazette Location Created Successfully');;

        return redirect()->route('gazette_locations.index')->with([
            'message' => 'Gazette Location Created Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function edit($id){
        $title = 'Edit GazetteLocation';
        $section = 'GazetteLocation';
        $section_url = route('gazette_locations.index');
        $sub_section = 'Edit';
        $gazette_location = GazetteLocation::find($id);
        return view('company.gazette_location.form', compact('title', 'section', 'sub_section', 'section_url', 'gazette_location'));
    }
    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
        ]);

        try{
            $gazette_location = GazetteLocation::find($id);

            Log::info('Creating');
            $gazette_location->update($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Info Updated Successfully');;

        return redirect()->route('gazette_locations.index')->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function delete($id){
        $gazette_location = GazetteLocation::find($id);
        $gazette_location->delete();
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}

