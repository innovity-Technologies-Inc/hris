<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Branch;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BranchesController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch){
        $title = 'Branch';
        $section = 'Company Setup';
        $sub_section = 'Branch';
        $query = Branch::query()->with(['getBank']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['name', 'address', 'routing_no', 'swift_code', 'getBank.name'];
        $branches = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company_setup.branch.search_results', compact('branches'))->render();
        }
        return view('company_setup.branch.index', compact('title', 'section', 'sub_section', 'branches'));
    }

    public function create(){
        $title = 'Add Branch';
        $section = 'Branch';
        $section_url = route('branches.index');
        $sub_section = 'Add';
        $banks = Bank::all()->sortBy('name');
        return view('company_setup.branch.form', compact('title', 'section', 'sub_section', 'section_url', 'banks'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'bank_id' => 'required',
            'address' => 'required|string',
            'routing_no' => 'required|string|max:255',
            'swift_code' => 'required|string|max:255',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'bank_id.required' => 'Please Choose Bank',
            'address.required' => 'Please Enter Address',
            'routing_no.required' => 'Please Enter Routing No',
            'routing_no.max' => 'Routing No Must Be Less Than 255 Characters',
            'swift_code.required' => 'Please Enter Swift Code',
            'swift_code.max' => 'Swift Code Must Be Less Than 255 Characters',
            'status.required' => 'Please Select Status',
        ]);

        try{
            Log::info('Adding Branch');
            Branch::create($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Branch Added Successfully');;

        return redirect()->route('branches.index')->with([
            'message' => 'Branch Added Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function edit($id){
        $title = 'Edit Branch';
        $section = 'Branch';
        $section_url = route('branches.index');
        $sub_section = 'Edit';
        $branch = Branch::find($id);
        $banks = Bank::all()->sortBy('name');
        return view('company_setup.branch.form', compact('title', 'section', 'sub_section', 'section_url', 'branch', 'banks'));
    }
    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'bank_id' => 'required',
            'address' => 'required|string',
            'routing_no' => 'required|string|max:255',
            'swift_code' => 'required|string|max:255',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'bank_id.required' => 'Please Choose Bank',
            'address.required' => 'Please Enter Address',
            'routing_no.required' => 'Please Enter Routing No',
            'routing_no.max' => 'Routing No Must Be Less Than 255 Characters',
            'swift_code.required' => 'Please Enter Swift Code',
            'swift_code.max' => 'Swift Code Must Be Less Than 255 Characters',
            'status.required' => 'Please Select Status',
        ]);

        try{
            $branch = Branch::find($id);

            Log::info('Creating');
            $branch->update($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Info Updated Successfully');

        return redirect()->route('branches.index')->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function delete($id){
        $branch = Branch::find($id);
        $branch->delete();
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}
