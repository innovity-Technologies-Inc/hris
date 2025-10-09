<?php

namespace App\Http\Controllers;

use App\HelperClass;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanySetupController extends Controller
{

    public function groupIndex(){
        $title = 'Group';
        $section = 'Company Setup';
        $sub_section = 'Group';
        $groups = Group::latest()->paginate(10);
        return view('company_setup.group', compact('title', 'section', 'sub_section', 'groups'));
    }
    public function groupSave(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
        ]);

        if (isset($request->id)){
            $group = Group::find($request->id);
            $group->update($request->all());
        }else{
            Group::create($request->all());
        }

        return redirect()->back()->with([
            'message' => 'Group Saved Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function groupDelete($id){
        $group = Group::find($id);
        $group->delete();
        return redirect()->back()->with([
            'message' => 'Group Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function companyTypeIndex(){
        $title = 'Company Type';
        $section = 'Company Setup';
        $sub_section = 'Company Type';
        $company_types = CompanyType::latest()->paginate(10);
        return view('company_setup.company_type', compact('title', 'section', 'sub_section', 'company_types'));
    }

    public function companyTypeSave(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'status' => 'required',
        ], [
            'short_name.required' => 'Please Enter Short Name',
            'short_name.max' => 'Short Name Must Be Less Than 10 Characters',
            'short_name.string' => 'Short Name Must Be String',
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
        ]);

        if (isset($request->id)){
            $type = CompanyType::find($request->id);
            $type->update($request->all());
        }else{
            CompanyType::create($request->all());
        }

        return redirect()->back()->with([
            'message' => 'Company Type Saved Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function companyTypeDelete($id){
        $company_type = CompanyType::find($id);
        $company_type->delete();
        return redirect()->back()->with([
            'message' => 'Company Type Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }


    public function companyIndex(){
        $title = 'Company';
        $section = 'Company Setup';
        $sub_section = 'Company';
        $companies = Company::latest()->paginate(10);
        return view('company_setup.company.index', compact('title', 'section', 'sub_section', 'companies'));
    }

    public function companyCreate(){
        $title = 'Add Company';
        $section = 'Company';
        $section_url = route('companies.index');
        $sub_section = 'Add';
        $company_types = CompanyType::all()->sortBy('name');
        $groups = Group::all()->sortBy('name');
        return view('company_setup.company.form', compact('title', 'section', 'sub_section', 'groups', 'company_types', 'section_url'));
    }

    public function companyStore(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'type_id' => 'required',
            'group_id' => 'required',
            'address' => 'required',
            'telephone' => 'required',
            'fax' => 'required',
            'email' => 'required|string|email',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'short_name.required' => 'Please Enter Short Name',
            'short_name.max' => 'Short Name Must Be Less Than 10 Characters',
            'short_name.string' => 'Short Name Must Be String',
            'type_id.required' => 'Please Select Type',
            'group_id.required' => 'Please Select Group',
            'address.required' => 'Please Enter Address',
            'telephone.required' => 'Please Enter Telephone',
            'fax.required' => 'Please Enter Fax',
            'email.required' => 'Please Enter Email Address',
            'email.email' => 'Please Enter Valid Email Address',
            'logo.required' => 'Please Upload Logo',
            'logo.image' => 'Please Upload Image File',
            'logo.mimes' => 'Please Upload Image File',
            'logo.max' => 'Logo Must Be Less Than 2 MB',
        ]);

        try{
            Log::info('Creating Data Array');
            $data = [
                'name' => $request->name,
                'short_name' => $request->short_name,
                'type_id' => $request->type_id,
                'group_id' => $request->group_id,
                'address' => $request->address,
                'telephone' => $request->telephone,
                'fax' => $request->fax,
                'email' => $request->email,
                'status' => $request->status,
            ];


            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                Log::info('Initating File Upload Process');
                $logo_path = HelperClass::file_upload($logo, 'company_logo');
                $data['logo'] = $logo_path;
            }

            Log::info('Creating Company');
            Company::create($data);

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Company Created Successfully');;

        return redirect()->route('companies.index')->with([
            'message' => 'Company Created Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function companyEdit($id){
        $title = 'Edit Company';
        $section = 'Company';
        $section_url = route('companies.index');
        $sub_section = 'Edit';
        $company_types = CompanyType::all()->sortBy('name');
        $groups = Group::all()->sortBy('name');
        $company = Company::find($id);
        return view('company_setup.company.form', compact('title', 'section', 'sub_section', 'groups', 'company_types', 'section_url', 'company'));
    }
    public function companyUpdate(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'type_id' => 'required',
            'group_id' => 'required',
            'address' => 'required',
            'telephone' => 'required',
            'fax' => 'required',
            'email' => 'required|string|email',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'short_name.required' => 'Please Enter Short Name',
            'short_name.max' => 'Short Name Must Be Less Than 10 Characters',
            'short_name.string' => 'Short Name Must Be String',
            'type_id.required' => 'Please Select Type',
            'group_id.required' => 'Please Select Group',
            'address.required' => 'Please Enter Address',
            'telephone.required' => 'Please Enter Telephone',
            'fax.required' => 'Please Enter Fax',
            'email.required' => 'Please Enter Email Address',
            'email.email' => 'Please Enter Valid Email Address',
            'logo.image' => 'Please Upload Image File',
            'logo.mimes' => 'Please Upload Image File',
            'logo.max' => 'Logo Must Be Less Than 2 MB',
        ]);

        try{
            Log::info('Creating Data Array');
            $company = Company::find($id);
            $data = [
                'name' => $request->name,
                'short_name' => $request->short_name,
                'type_id' => $request->type_id,
                'group_id' => $request->group_id,
                'address' => $request->address,
                'telephone' => $request->telephone,
                'fax' => $request->fax,
                'email' => $request->email,
                'status' => $request->status,
            ];


            if ($request->hasFile('logo')) {
                if($company->logo != null)
                {
                    Log::info('Deleting Old Logo');
                    HelperClass::file_delete($company->logo);
                    Log::info('Old Logo Deleted Successfully');

                }

                $logo = $request->file('logo');
                Log::info('Initating File Upload Process');
                $logo_path = HelperClass::file_upload($logo, 'company_logo');
                $data['logo'] = $logo_path;
            }

            Log::info('Creating Company');
            $company->update($data);

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Info Updated Successfully');;

        return redirect()->route('companies.index')->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function companyDelete($id){
        $company = Company::find($id);
        if($company->logo != null)
        {
            Log::info('Deleting Logo');
            HelperClass::file_delete($company->logo);
            Log::info('Logo Deleted Successfully');
        }
        $company->delete();
        return redirect()->back()->with([
            'message' => 'Company Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }


}
