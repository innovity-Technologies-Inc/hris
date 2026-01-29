<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BanksController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch){
        $title = 'Bank';
        $section = 'Company Setup';
        $sub_section = 'Bank';
        $query = Bank::query();
        $searchTerm = $request->get('keyword');
        $searchableFields = ['name', 'short_name', 'bank_code', 'contact_no', 'contact_person', 'contact_person_no'];
        $banks = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company_setup.bank.search_results', compact('banks'))->render();
        }
        return view('company_setup.bank.index', compact('title', 'section', 'sub_section', 'banks'));
    }

    public function create(){
        $title = 'Add Bank';
        $section = 'Bank';
        $section_url = route('banks.index');
        $sub_section = 'Add';
        return view('company_setup.bank.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'bank_code' => 'required|string|max:10',
            'contact_no' => 'required|digits_between:4,15',
            'contact_person' => 'required|string|max:255',
            'contact_person_no' => 'required|digits_between:4,15',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'short_name.required' => 'Please Enter Short Name',
            'short_name.max' => 'Short Name Must Be Less Than 10 Characters',
            'bank_code.required' => 'Please Enter Bank Code',
            'bank_code.max' => 'Bank Code Must Be Less Than 10 Characters',
            'contact_no.required' => 'Please Enter Contact No',
            'contact_no.digits_between' => 'Contact No Must Be Between 4 And 15 Digits',
            'contact_person.required' => 'Please Enter Contact Person Name',
            'contact_person.max' => 'Contact Person Name Must Be Less Than 255 Characters',
            'contact_person_no.required' => 'Please Enter Contact Person No',
            'contact_person_no.digits_between' => 'Contact Person No Must Be Between 4 And 15 Digits',
            'status.required' => 'Please Select Status',
        ]);

        try{
            Log::info('Adding Bank');
            Bank::create($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Bank Added Successfully');;

        return redirect()->route('banks.index')->with([
            'message' => 'Bank Added Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function edit($id){
        $title = 'Edit Bank';
        $section = 'Bank';
        $section_url = route('banks.index');
        $sub_section = 'Edit';
        $bank = Bank::find($id);
        return view('company_setup.bank.form', compact('title', 'section', 'sub_section', 'section_url', 'bank'));
    }
    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'bank_code' => 'required|string|max:10',
            'contact_no' => 'required|digits_between:4,15',
            'contact_person' => 'required|string|max:255',
            'contact_person_no' => 'required|digits_between:4,15',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'short_name.required' => 'Please Enter Short Name',
            'short_name.max' => 'Short Name Must Be Less Than 10 Characters',
            'bank_code.required' => 'Please Enter Bank Code',
            'bank_code.max' => 'Bank Code Must Be Less Than 10 Characters',
            'contact_no.required' => 'Please Enter Contact No',
            'contact_no.digits_between' => 'Contact No Must Be Between 4 And 15 Digits',
            'contact_person.required' => 'Please Enter Contact Person Name',
            'contact_person.max' => 'Contact Person Name Must Be Less Than 255 Characters',
            'contact_person_no.required' => 'Please Enter Contact Person No',
            'contact_person_no.digits_between' => 'Contact Person No Must Be Between 4 And 15 Digits',
            'status.required' => 'Please Select Status',
        ]);

        try{
            $bank = Bank::find($id);

            Log::info('Creating');
            $bank->update($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Info Updated Successfully');

        return redirect()->route('banks.index')->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function delete($id){
        $bank = Bank::find($id);
        $bank->delete();
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}
