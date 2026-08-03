<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

use App\HelperClass;
use App\Models\Company\Company;
use App\Models\Company\CompanyType;
use App\Models\Company\Group;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanySetupController extends Controller
{

    public function groupIndex(Request $request, FlexSearch $flexsearch){
        $title = 'Group';
        $section = 'Company Setup';
        $sub_section = 'Group';
        $group = Group::first();
        /*$query = Group::query();
        $searchTerm = $request->get('keyword');
        $searchableFields = ['name'];
        $groups = $flexsearch->apply( $query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company.group_search_results', compact('groups'))->render();
        }*/
        return view('company.groups', compact('title', 'section', 'sub_section', 'group'));
    }
    public function groupSave(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
        ]);

        if ($request->type == 'edit'){
            $group = Group::find($request->id);
            $group->update([
                'name' => $request->name,
                'status' => 'active'
            ]);
        }else{
            Group::create([
                'name' => $request->name,
                'status' => 'active'
            ]);
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

    public function companyTypeIndex(Request $request, FlexSearch $flexsearch){
        $title = 'Company Type';
        $section = 'Company Setup';
        $sub_section = 'Company Type';
        $query = CompanyType::query();
        $searchTerm = $request->get('keyword');
        $searchableFields = ['name', 'short_name'];
        $company_types = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company.company_type_search_results', compact('company_types'))->render();
        }
        return view('company.company_type', compact('title', 'section', 'sub_section', 'company_types'));
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


    public function companyIndex(Request $request, FlexSearch $flexsearch){
        $title = 'Company';
        $section = 'Company Setup';
        $sub_section = 'Company';
        $query = Company::query()->with(['getCompanyType', 'getGroup']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['name', 'short_name', 'getCompanyType.name', 'getGroup.name', 'address', 'email'];
        $companies = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company.company.search_results', compact('companies'))->render();
        }
        return view('company.company.index', compact('title', 'section', 'sub_section', 'companies'));
    }

    public function companyCreate(){
        $title = 'Add Company';
        $section = 'Company';
        $section_url = route('companies.index');
        $sub_section = 'Add';
        $company_types = CompanyType::all()->sortBy('name');
        $groups = Group::all()->sortBy('name');
        return view('company.company.form', compact('title', 'section', 'sub_section', 'groups', 'company_types', 'section_url'));
    }

    public function companyStore(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'type_id' => 'required',
            'group_id' => 'required',
            'address' => 'required',
            'telephone' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'email' => 'nullable|string|email|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:webp,jpeg,png,jpg,gif,svg|max:2048',
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
            'telephone.string' => 'Telephone Must Be Text',
            'telephone.max' => 'Telephone Must Be Less Than 50 Characters',
            'fax.string' => 'Fax Must Be Text',
            'fax.max' => 'Fax Must Be Less Than 50 Characters',
            'email.email' => 'Please Enter Valid Email Address',
            'email.max' => 'Email Must Be Less Than 255 Characters',
            'website.string' => 'Website Must Be Text',
            'website.max' => 'Website Must Be Less Than 255 Characters',
            'logo.image' => 'Please Upload Image File',
            'logo.mimes' => 'Please Upload Image File (webp, jpeg, png, jpg, gif, svg)',
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
                'website' => $request->website,
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
        return view('company.company.form', compact('title', 'section', 'sub_section', 'groups', 'company_types', 'section_url', 'company'));
    }
    public function companyUpdate(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'type_id' => 'required',
            'group_id' => 'required',
            'address' => 'required',
            'telephone' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'email' => 'nullable|string|email|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:webp,jpeg,png,jpg,gif,svg|max:2048',
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
            'telephone.string' => 'Telephone Must Be Text',
            'telephone.max' => 'Telephone Must Be Less Than 50 Characters',
            'fax.string' => 'Fax Must Be Text',
            'fax.max' => 'Fax Must Be Less Than 50 Characters',
            'email.email' => 'Please Enter Valid Email Address',
            'email.max' => 'Email Must Be Less Than 255 Characters',
            'website.string' => 'Website Must Be Text',
            'website.max' => 'Website Must Be Less Than 255 Characters',
            'logo.image' => 'Please Upload Image File',
            'logo.mimes' => 'Please Upload Image File (webp, jpeg, png, jpg, gif, svg)',
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
                'website' => $request->website,
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

