<?php

namespace App\Http\Controllers;

use App\HelperClass;
use App\Models\IDCardDesign;
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

        return view('settings.id_design.index', compact(
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

        return view('settings.id_design.create', compact(
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
            'design_file' => 'required|file|max:2048',
            'preview_front_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preview_back_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'theme_name.required' => 'Please enter a theme name',
            'theme_name.unique' => 'This theme name already exists',
            'design_file.required' => 'Please upload a design file',
            'design_file.max' => 'File size must be less than 2MB',
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

        \Log::info('Extension validation passed');

        try {
            DB::beginTransaction();
            \Log::info('Transaction started');

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

            return redirect()->route('settings.id_design.index')
                           ->with('success', 'ID Card Design created successfully');

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
                           ->with('error', 'Failed to create design: ' . $e->getMessage())
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

        return view('settings.id_design.show', compact(
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
                               ->with('info', 'This design is already active');
            }

            // Check if file exists before activating
            if (!Storage::disk('public')->exists($design->file_path)) {
                return redirect()->back()
                               ->with('error', 'Cannot activate: Design file not found');
            }

            // Deactivate all other designs
            IDCardDesign::where('id', '!=', $design->id)
                        ->where('status', 'active')
                        ->update(['status' => 'inactive']);

            // Activate this design
            $design->update(['status' => 'active']);

            return redirect()->back()
                           ->with('success', 'Design activated successfully. All other designs have been deactivated.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to activate design: ' . $e->getMessage());
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
                               ->with('info', 'This design is already inactive');
            }

            $design->update(['status' => 'inactive']);

            return redirect()->back()
                           ->with('success', 'Design deactivated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to deactivate design: ' . $e->getMessage());
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

        // Sample employee data for preview
        $employee = (object) [
            'full_name' => 'John Doe',
            'system_id' => 'EMP-12345',
            'designation' => 'Senior Developer',
            'department' => 'IT Department',
            'photo_path' => null,
            'blood_group' => 'O+',
            'joining_date' => '2023-01-15',
            'valid_until' => '2025-12-31'
        ];

        $company = (object) [
            'name' => 'Your Company Name',
            'logo_light' => null
        ];

        // Get file content and evaluate as Blade template
        $fileContent = Storage::disk('public')->get($design->file_path);

        // Create a temporary view from the file content
        return view('shared.blade_renderer', [
            'content' => $fileContent,
            'employee' => $employee,
            'company' => $company
        ]);
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
                               ->with('error', 'Cannot delete an active design. Please activate another design first.');
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

            return redirect()->route('settings.id_design.index')
                           ->with('success', 'Design deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete design: ' . $e->getMessage());
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
}
