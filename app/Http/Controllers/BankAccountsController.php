<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Branch;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BankAccountsController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch){
        $title = 'Bank Account';
        $section = 'Company Setup';
        $sub_section = 'Bank Account';
        $query = BankAccount::query()->with(['getBank', 'getBranch']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['account_no', 'holder_name', 'account_type', 'contact_person', 'email', 'getBank.name', 'getBranch.name'];
        $bank_accounts = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company_setup.bank_accounts.search_results', compact('bank_accounts'))->render();
        }
        return view('company_setup.bank_accounts.index', compact('title', 'section', 'sub_section', 'bank_accounts'));
    }

    public function create(){
        $title = 'Add Bank Account';
        $section = 'Bank Account';
        $section_url = route('bank_accounts.index');
        $sub_section = 'Add';
        $banks = Bank::all()->sortBy('name');
        $branches = Branch::all()->sortBy('name');
        return view('company_setup.bank_accounts.form', compact('title', 'section', 'sub_section', 'section_url', 'banks', 'branches'));
    }

    public function store(Request $request){
        $request->validate([
            'bank_id' => 'required',
            'branch_id' => 'required',
            'account_no' => 'required',
            'holder_name' => 'required',
            'account_type' => 'required',
            'contact_person' => 'required',
            'contact_person_no' => 'required',
            'email' => 'required',
            'status' => 'required',
        ], [
            'bank_id.required' => 'Please Choose Bank',
            'branch_id.required' => 'Please Choose Branch',
            'account_no.required' => 'Please Enter Account No',
            'holder_name.required' => 'Please Enter Account Holder Name',
            'account_type.required' => 'Please Choose Account Type',
            'contact_person.required' => 'Please Enter Contact Person Name',
            'contact_person_no.required' => 'Please Enter Contact Person Phone No',
            'email.required' => 'Please Enter Email Address',
            'status.required' => 'Please Select Status',
        ]);

        try{
            Log::info('Adding Bank Account');
            BankAccount::create($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Bank Account Added Successfully');;

        return redirect()->route('bank_accounts.index')->with([
            'message' => 'Bank Account Added Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function edit($id){
        $title = 'Edit Bank Account';
        $section = 'Bank Account';
        $section_url = route('bank_accounts.index');
        $sub_section = 'Edit';
        $bank_account = BankAccount::find($id);
        $banks = Bank::all()->sortBy('name');
        $branches = Branch::all()->sortBy('name');
        return view('company_setup.branch.form', compact('title', 'section', 'sub_section', 'section_url', 'branches', 'banks', 'bank_account'));
    }
    public function update(Request $request, $id){
        $request->validate([
            'bank_id' => 'required',
            'branch_id' => 'required',
            'account_no' => 'required',
            'holder_name' => 'required',
            'account_type' => 'required',
            'contact_person' => 'required',
            'contact_person_no' => 'required',
            'email' => 'required',
            'status' => 'required',
        ], [
            'bank_id.required' => 'Please Choose Bank',
            'branch_id.required' => 'Please Choose Branch',
            'account_no.required' => 'Please Enter Account No',
            'holder_name.required' => 'Please Enter Account Holder Name',
            'account_type.required' => 'Please Choose Account Type',
            'contact_person.required' => 'Please Enter Contact Person Name',
            'contact_person_no.required' => 'Please Enter Contact Person Phone No',
            'email.required' => 'Please Enter Email Address',
            'status.required' => 'Please Select Status',
        ]);

        try{
            $bank_account = BankAccount::find($id);

            Log::info('Creating');
            $bank_account->update($request->all());

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Info Updated Successfully');

        return redirect()->route('bank_accounts.index')->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function delete($id){
        $bank_account = BankAccount::find($id);
        $bank_account->delete();
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}
