<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\HelperClass;
use App\Models\Setting\IDCardDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class IDCardDesignController extends Controller
{
    /**
     * Display a listing of all ID card designs
     */
    public function index()
    {
        $title = 'ID Card Designs';
        $section = 'Settings';
        $sub_section = 'ID Card Design';

        $designs = IDCardDesign::orderBy('status', 'desc')
                               ->orderBy('created_at', 'desc')
                               ->get();

        $activeDesign = IDCardDesign::where('status', 'active')->first();

        return view('setting.id_design.index', compact(
            'title',
            'section',
            'sub_section',
            'designs',
            'activeDesign'
        ));
    }

    /**
     * Show the form for creating a new ID card design
     */
    public function create()
    {
        $title = 'Create ID Card Design';
        $section = 'Settings';
        $sub_section = 'ID Card Design';

        return view('setting.id_design.create', compact(
            'title',
            'section',
            'sub_section'
        ));
    }

    /**
     * Store a newly created ID card design
     */
    public function store(Request $request)
    {
        \Log::info('=== ID Card Design Store Started ===');
        \Log::info('Request Data:', $request->except(['design_file', 'preview_front_card', 'preview_back_card']));
        \Log::info('Has design_file: ' . ($request->hasFile('design_file') ? 'Yes' : 'No'));
        \Log::info('Has preview_front_card: ' . ($request->hasFile('preview_front_card') ? 'Yes' : 'No'));
        \Log::info('Has preview_back_card: ' . ($request->hasFile('preview_back_card') ? 'Yes' : 'No'));

        $validator = Validator::make($request->all(), [
            'theme_name' => 'required|string|max:255|unique:id_card_designs,theme_name',
            'description' => 'nullable|string|max:1000',
            'template_source' => 'required|in:preloaded,upload',
            'design_file' => 'required_if:template_source,upload|file|max:2048',
            'preloaded_template' => 'required_if:template_source,preloaded|string|in:design_1,design_2,design_3,design_4',
            'preview_front_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preview_back_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'theme_name.required' => 'Please enter a theme name',
            'theme_name.unique' => 'This theme name already exists',
            'design_file.required_if' => 'Please upload a design file',
            'design_file.max' => 'File size must be less than 2MB',
            'preloaded_template.required_if' => 'Please select a demo template',
            'preview_front_card.image' => 'Front card preview must be an image file',
            'preview_front_card.mimes' => 'Front card preview must be jpeg, png, jpg, or gif',
            'preview_front_card.max' => 'Front card preview must be less than 2MB',
            'preview_back_card.image' => 'Back card preview must be an image file',
            'preview_back_card.mimes' => 'Back card preview must be jpeg, png, jpg, or gif',
            'preview_back_card.max' => 'Back card preview must be less than 2MB'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation Failed:', $validator->errors()->toArray());
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        \Log::info('Validation passed');

        if ($request->template_source === 'upload') {
            // Validate file extension manually
            $designFile = $request->file('design_file');
            $extension = strtolower($designFile->getClientOriginalExtension());
            \Log::info('File extension: ' . $extension);
            \Log::info('Original filename: ' . $designFile->getClientOriginalName());

            if (!in_array($extension, ['php', 'blade'])) {
                \Log::error('Invalid file extension: ' . $extension);
                return redirect()->back()
                               ->withErrors(['design_file' => 'Only .blade.php or .php files are allowed'])
                               ->withInput();
            }
        }

        \Log::info('Extension validation passed');

        try {
            DB::beginTransaction();
            \Log::info('Transaction started');

            $designFilePath = null;
            if ($request->template_source === 'preloaded') {
                $templateName = $request->preloaded_template;
                $sourcePath = resource_path('views/setting/id_design/designs/' . $templateName . '.blade.php');
                if (!file_exists($sourcePath)) {
                    throw new \Exception("Preloaded template '{$templateName}' does not exist.");
                }
                $fileContent = file_get_contents($sourcePath);

                $disk = config('filesystems.default');
                $filename = time() . Str::random(10) . '.php';
                $designFilePath = 'upload/id_card_designs/designs/' . $filename;
                Storage::disk($disk)->put($designFilePath, $fileContent, [
                    'visibility' => 'public'
                ]);
                \Log::info('Preloaded design template copied to: ' . $designFilePath);
            } else {
                // Validate and sanitize the uploaded file content
                $uploadedFile = $request->file('design_file');
                $fileContent = file_get_contents($uploadedFile->getRealPath());
                \Log::info('File content length: ' . strlen($fileContent) . ' bytes');

                // Security check: prevent dangerous PHP functions with function call patterns
                $dangerousFunctions = [
                    'eval', 'exec', 'shell_exec', 'passthru',
                    'proc_open', 'popen', 'curl_exec', 'curl_multi_exec',
                    'parse_ini_file', 'show_source'
                ];

                foreach ($dangerousFunctions as $func) {
                    // Check for function call pattern: function_name followed by (
                    if (preg_match('/\b' . preg_quote($func, '/') . '\s*\(/i', $fileContent)) {
                        \Log::error('Security violation detected: ' . $func);
                        throw new \Exception("Security violation: Dangerous function '{$func}()' detected in template");
                    }
                }

                \Log::info('Security check passed');

                // Upload design file using HelperClass
                $designFilePath = HelperClass::file_upload($uploadedFile, 'id_card_designs/designs');
                \Log::info('Design file uploaded: ' . $designFilePath);
            }

            // Handle front card preview upload
            $previewFrontCardPath = null;
            if ($request->hasFile('preview_front_card')) {
                $previewFrontCardPath = HelperClass::file_upload($request->file('preview_front_card'), 'id_card_designs/previews');
                \Log::info('Front card preview uploaded: ' . $previewFrontCardPath);
            }

            // Handle back card preview upload
            $previewBackCardPath = null;
            if ($request->hasFile('preview_back_card')) {
                $previewBackCardPath = HelperClass::file_upload($request->file('preview_back_card'), 'id_card_designs/previews');
                \Log::info('Back card preview uploaded: ' . $previewBackCardPath);
            }

            // Create design with inactive status
            \Log::info('Creating design record...');
            $design = IDCardDesign::create([
                'theme_name' => $request->theme_name,
                'file_path' => $designFilePath,
                'description' => $request->description,
                'preview_front_card' => $previewFrontCardPath,
                'preview_back_card' => $previewBackCardPath,
                'status' => 'inactive'
            ]);

            \Log::info('Design created with ID: ' . $design->id);

            DB::commit();
            \Log::info('Transaction committed successfully');
            \Log::info('=== ID Card Design Store Completed Successfully ===');

            return redirect()->route('setting.id_design.index')
                           ->with('message', 'ID Card Design created successfully')
                           ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Exception caught: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Clean up uploaded files on error using HelperClass
            if (isset($designFilePath)) {
                HelperClass::file_delete($designFilePath);
                \Log::info('Cleaned up design file');
            }
            if (isset($previewFrontCardPath)) {
                HelperClass::file_delete($previewFrontCardPath);
                \Log::info('Cleaned up front card preview');
            }
            if (isset($previewBackCardPath)) {
                HelperClass::file_delete($previewBackCardPath);
                \Log::info('Cleaned up back card preview');
            }

            \Log::info('=== ID Card Design Store Failed ===');

            return redirect()->back()
                           ->with('message', 'Failed to create design: ' . $e->getMessage())
                           ->with('alert-type', 'error')
                           ->withInput();
        }
    }

    /**
     * Display the specified ID card design
     */
    public function show($id)
    {
        $design = IDCardDesign::findOrFail($id);

        $title = $design->theme_name;
        $section = 'Settings';
        $sub_section = 'ID Card Design';

        return view('setting.id_design.show', compact(
            'title',
            'section',
            'sub_section',
            'design'
        ));
    }

    /**
     * Activate a specific ID card design
     */
    public function activate($id)
    {
        try {
            $design = IDCardDesign::findOrFail($id);

            if ($design->status === 'active') {
                return redirect()->back()
                               ->with('message', 'This design is already active')
                               ->with('alert-type', 'info');
            }

            // Check if file exists before activating
            if (!Storage::disk('public')->exists($design->file_path)) {
                return redirect()->back()
                               ->with('message', 'Cannot activate: Design file not found')
                               ->with('alert-type', 'error');
            }

            // Deactivate all other designs
            IDCardDesign::where('id', '!=', $design->id)
                        ->where('status', 'active')
                        ->update(['status' => 'inactive']);

            // Activate this design
            $design->update(['status' => 'active']);

            return redirect()->back()
                           ->with('message', 'Design activated successfully. All other designs have been deactivated.')
                           ->with('alert-type', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('message', 'Failed to activate design: ' . $e->getMessage())
                           ->with('alert-type', 'error');
        }
    }

    /**
     * Deactivate a specific ID card design
     */
    public function deactivate($id)
    {
        try {
            $design = IDCardDesign::findOrFail($id);

            if ($design->status !== 'active') {
                return redirect()->back()
                               ->with('message', 'This design is already inactive')
                               ->with('alert-type', 'info');
            }

            $design->update(['status' => 'inactive']);

            return redirect()->back()
                           ->with('message', 'Design deactivated successfully')
                           ->with('alert-type', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('message', 'Failed to deactivate design: ' . $e->getMessage())
                           ->with('alert-type', 'error');
        }
    }

    /**
     * Preview a design template
     */
    public function preview($id)
    {
        $design = IDCardDesign::findOrFail($id);

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($design->file_path)) {
            abort(404, 'Design template file not found');
        }

        // Sample employee data for preview (containing all attributes used in themes)
        $employee = (object) [
            'id' => null,
            'system_id' => 'EMP-12345',
            'full_name' => 'John Doe',
            'personal_mobile' => '+880-1712-345678',
            'work_email' => 'john.doe@company.com',
            'personal_email' => 'john.doe@gmail.com',
            'blood_group' => 'O+',
            'photo_path' => null,
            'joining_date' => '2023-01-15',
            'valid_until' => '2025-12-31'
        ];

        $company = (object) [
            'name' => 'Your Company Name',
            'logo_light' => null
        ];

        $generalSettings = \App\Models\Setting\GeneralSetting::first();

        // Prepare company info
        $companyInfo = (object) [
            'name' => $generalSettings?->company_name ?? 'Company Name',
            'logo' => $generalSettings?->logo ?? null,
            'website' => $generalSettings?->website ?? 'www.company.com',
            'telephone' => $generalSettings?->contact_phone ?? '',
            'fax' => '',
            'email' => $generalSettings?->email ?? '',
            'address' => $generalSettings?->address ?? '',
            'city' => $generalSettings?->city ?? '',
            'state' => $generalSettings?->state ?? '',
            'zip_code' => $generalSettings?->zip_code ?? '',
            'country' => $generalSettings?->country ?? '',
        ];

        // Create a temporary view file to compile dynamic Blade content properly
        $tempViewName = 'id_card_preview_' . uniqid();
        $tempFileName = $tempViewName . '.blade.php';
        $tempPath = resource_path('views/temp/' . $tempFileName);

        // Ensure temp directory exists
        $tempDir = dirname($tempPath);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Copy template file from storage to temp views folder
        $fullPath = Storage::disk('public')->path($design->file_path);
        copy($fullPath, $tempPath);

        try {
            // Render the Blade template view to HTML string
            $html = view('temp.' . $tempViewName, [
                'employee' => $employee,
                'company' => $company,
                'officeInfo' => null,
                'currentCompany' => null,
                'currentDesignation' => null,
                'currentDepartment' => null,
                'companyInfo' => $companyInfo,
                'generalSettings' => $generalSettings
            ])->render();

            return response($html);
        } finally {
            // Clean up temporary view file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }

    /**
     * Remove the specified ID card design
     */
    public function destroy($id)
    {
        try {
            $design = IDCardDesign::findOrFail($id);

            // Prevent deletion of active design
            if ($design->status === 'active') {
                return redirect()->back()
                               ->with('message', 'Cannot delete an active design. Please activate another design first.')
                               ->with('alert-type', 'error');
            }

            // Delete design file using HelperClass
            if (Storage::disk('public')->exists($design->file_path)) {
                HelperClass::file_delete($design->file_path);
            }

            // Delete preview image if exists using HelperClass
            if ($design->preview_image) {
                HelperClass::file_delete($design->preview_image);
            }

            // Delete front card preview if exists
            if ($design->preview_front_card) {
                HelperClass::file_delete($design->preview_front_card);
            }

            // Delete back card preview if exists
            if ($design->preview_back_card) {
                HelperClass::file_delete($design->preview_back_card);
            }

            // Delete database record
            $design->delete();

            return redirect()->route('setting.id_design.index')
                           ->with('message', 'Design deleted successfully')
                           ->with('alert-type', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('message', 'Failed to delete design: ' . $e->getMessage())
                           ->with('alert-type', 'error');
        }
    }

    /**
     * Download the design template file
     */
    public function download($id)
    {
        $design = IDCardDesign::findOrFail($id);

        if (!Storage::disk('public')->exists($design->file_path)) {
            abort(404, 'Design file not found');
        }

        return response()->download(
            Storage::disk('public')->path($design->file_path),
            basename($design->file_path)
        );
    }

    /**
     * Show the form for editing an ID card design
     */
    public function edit($id)
    {
        $design = IDCardDesign::findOrFail($id);

        $title = 'Edit ID Card Design';
        $section = 'Settings';
        $sub_section = 'ID Card Design';

        return view('setting.id_design.edit', compact(
            'title',
            'section',
            'sub_section',
            'design'
        ));
    }

    /**
     * Update an ID card design
     */
    public function update(Request $request, $id)
    {
        $design = IDCardDesign::findOrFail($id);

        \Log::info('=== ID Card Design Update Started ===');
        \Log::info('Request Data:', $request->except(['design_file', 'preview_front_card', 'preview_back_card']));

        $validator = Validator::make($request->all(), [
            'theme_name' => 'required|string|max:255|unique:id_card_designs,theme_name,' . $id,
            'description' => 'nullable|string|max:1000',
            'template_source' => 'required|in:preloaded,upload,keep_existing',
            'design_file' => 'required_if:template_source,upload|file|max:2048',
            'preloaded_template' => 'required_if:template_source,preloaded|string|in:design_1,design_2,design_3,design_4',
            'preview_front_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preview_back_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'theme_name.required' => 'Please enter a theme name',
            'theme_name.unique' => 'This theme name already exists',
            'design_file.required_if' => 'Please upload a design file',
            'design_file.max' => 'File size must be less than 2MB',
            'preloaded_template.required_if' => 'Please select a demo template',
            'preview_front_card.image' => 'Front card preview must be an image file',
            'preview_front_card.mimes' => 'Front card preview must be jpeg, png, jpg, or gif',
            'preview_front_card.max' => 'Front card preview must be less than 2MB',
            'preview_back_card.image' => 'Back card preview must be an image file',
            'preview_back_card.mimes' => 'Back card preview must be jpeg, png, jpg, or gif',
            'preview_back_card.max' => 'Back card preview must be less than 2MB'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation Failed:', $validator->errors()->toArray());
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        if ($request->template_source === 'upload') {
            $designFile = $request->file('design_file');
            $extension = strtolower($designFile->getClientOriginalExtension());
            if (!in_array($extension, ['php', 'blade'])) {
                return redirect()->back()
                               ->withErrors(['design_file' => 'Only .blade.php or .php files are allowed'])
                               ->withInput();
            }
        }

        try {
            DB::beginTransaction();

            $oldDesignFilePath = null;
            $designFilePath = $design->file_path;

            if ($request->template_source === 'preloaded') {
                $templateName = $request->preloaded_template;
                $sourcePath = resource_path('views/setting/id_design/designs/' . $templateName . '.blade.php');
                if (!file_exists($sourcePath)) {
                    throw new \Exception("Preloaded template '{$templateName}' does not exist.");
                }
                $fileContent = file_get_contents($sourcePath);

                $disk = config('filesystems.default');
                $filename = time() . Str::random(10) . '.php';
                $newFilePath = 'upload/id_card_designs/designs/' . $filename;
                Storage::disk($disk)->put($newFilePath, $fileContent, [
                    'visibility' => 'public'
                ]);

                // Mark old file for deletion
                $oldDesignFilePath = $design->file_path;
                $designFilePath = $newFilePath;
            } elseif ($request->template_source === 'upload') {
                $uploadedFile = $request->file('design_file');
                $fileContent = file_get_contents($uploadedFile->getRealPath());

                $dangerousFunctions = [
                    'eval', 'exec', 'shell_exec', 'passthru',
                    'proc_open', 'popen', 'curl_exec', 'curl_multi_exec',
                    'parse_ini_file', 'show_source'
                ];

                foreach ($dangerousFunctions as $func) {
                    if (preg_match('/\b' . preg_quote($func, '/') . '\s*\(/i', $fileContent)) {
                        throw new \Exception("Security violation: Dangerous function '{$func}()' detected in template");
                    }
                }

                $newFilePath = HelperClass::file_upload($uploadedFile, 'id_card_designs/designs');
                $oldDesignFilePath = $design->file_path;
                $designFilePath = $newFilePath;
            }

            // Preview updates
            $previewFrontCardPath = $design->preview_front_card;
            $oldFrontPath = null;
            if ($request->hasFile('preview_front_card')) {
                $previewFrontCardPath = HelperClass::file_upload($request->file('preview_front_card'), 'id_card_designs/previews');
                $oldFrontPath = $design->preview_front_card;
            }

            $previewBackCardPath = $design->preview_back_card;
            $oldBackPath = null;
            if ($request->hasFile('preview_back_card')) {
                $previewBackCardPath = HelperClass::file_upload($request->file('preview_back_card'), 'id_card_designs/previews');
                $oldBackPath = $design->preview_back_card;
            }

            $design->update([
                'theme_name' => $request->theme_name,
                'file_path' => $designFilePath,
                'description' => $request->description,
                'preview_front_card' => $previewFrontCardPath,
                'preview_back_card' => $previewBackCardPath,
            ]);

            DB::commit();

            // Delete old files from storage on success
            if ($oldDesignFilePath && Storage::disk('public')->exists($oldDesignFilePath)) {
                HelperClass::file_delete($oldDesignFilePath);
            }
            if ($oldFrontPath && Storage::disk('public')->exists($oldFrontPath)) {
                HelperClass::file_delete($oldFrontPath);
            }
            if ($oldBackPath && Storage::disk('public')->exists($oldBackPath)) {
                HelperClass::file_delete($oldBackPath);
            }

            return redirect()->route('setting.id_design.index')
                           ->with('message', 'ID Card Design updated successfully')
                           ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            // Cleanup newly uploaded files on failure
            if (isset($newFilePath) && $newFilePath !== $design->file_path) {
                HelperClass::file_delete($newFilePath);
            }
            if (isset($previewFrontCardPath) && $previewFrontCardPath !== $design->preview_front_card) {
                HelperClass::file_delete($previewFrontCardPath);
            }
            if (isset($previewBackCardPath) && $previewBackCardPath !== $design->preview_back_card) {
                HelperClass::file_delete($previewBackCardPath);
            }

            return redirect()->back()
                           ->with('message', 'Failed to update design: ' . $e->getMessage())
                           ->with('alert-type', 'error')
                           ->withInput();
        }
    }
}

