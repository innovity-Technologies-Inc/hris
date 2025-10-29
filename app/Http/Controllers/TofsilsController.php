<?php

namespace App\Http\Controllers;

use App\Models\Tofsil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TofsilsController extends Controller
{
    public function index(){
        $title = 'Act';
        $section = 'Company Setup';
        $sub_section = 'Act';
        $tofsils = Tofsil::latest()->paginate(10);
        return view('company_setup.tofsil.index', compact('title', 'section', 'sub_section', 'tofsils'));
    }

    public function create(){
        $title = 'Add Act';
        $section = 'Act';
        $section_url = route('tofsils.index');
        $sub_section = 'Add';
        return view('company_setup.tofsil.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'description.required' => 'Please Enter Description',
        ]);

        try{
            Log::info('Creating Tofsil');
            Tofsil::create($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Act Created Successfully');;

        return redirect()->route('tofsils.index')->with([
            'message' => 'Act Created Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function edit($id){
        $title = 'Edit Act';
        $section = 'Act';
        $section_url = route('tofsils.index');
        $sub_section = 'Edit';
        $tofsil = Tofsil::find($id);
        return view('company_setup.tofsil.form', compact('title', 'section', 'sub_section', 'section_url', 'tofsil'));
    }
    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'description.required' => 'Please Enter Description',
        ]);

        try{
            $tofsil = Tofsil::find($id);

            Log::info('Updating Act');
            $tofsil->update($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Info Updated Successfully');;

        return redirect()->route('tofsils.index')->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function delete($id){
        $tofsil = Tofsil::find($id);
        $tofsil->delete();
        return redirect()->back()->with([
            'message' => 'Tofsil Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}
