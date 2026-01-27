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
        $validator = Validator::make($request->all(), [
            'theme_name' => 'required|string|max:255|unique:id_card_designs,theme_name',
            'description' => 'nullable|string|max:1000',
            'design_file' => 'required|file|mimes:blade.php,php|max:2048',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'theme_name.required' => 'Please enter a theme name',
            'theme_name.unique' => 'This theme name already exists',
            'design_file.required' => 'Please upload a design file',
            'design_file.mimes' => 'Only .blade.php or .php files are allowed',
            'design_file.max' => 'File size must be less than 2MB',
            'preview_image.image' => 'Preview must be an image file',
            'preview_image.mimes' => 'Preview image must be jpeg, png, jpg, or gif',
            'preview_image.max' => 'Preview image must be less than 2MB'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // Validate and sanitize the uploaded file content
            $uploadedFile = $request->file('design_file');
            $fileContent = file_get_contents($uploadedFile->getRealPath());

            // Security check: prevent dangerous PHP functions
            $dangerousFunctions = [
                'eval', 'exec', 'system', 'shell_exec', 'passthru',
                'proc_open', 'popen', 'curl_exec', 'curl_multi_exec',
                'parse_ini_file', 'show_source', 'file_put_contents'
            ];

            foreach ($dangerousFunctions as $func) {
                if (stripos($fileContent, $func) !== false) {
                    throw new \Exception("Security violation: Dangerous function '{$func}' detected in template");
                }
            }

            // Upload design file using HelperClass
            $designFilePath = HelperClass::file_upload($uploadedFile, 'id_card_designs/designs');

            // Handle preview image upload using HelperClass
            $previewImagePath = null;
            if ($request->hasFile('preview_image')) {
                $previewImagePath = HelperClass::file_upload($request->file('preview_image'), 'id_card_designs/previews');
            }

            // Create design with inactive status
            $design = IDCardDesign::create([
                'theme_name' => $request->theme_name,
                'file_path' => $designFilePath,
                'description' => $request->description,
                'preview_image' => $previewImagePath,
                'status' => 'inactive'
            ]);

            DB::commit();

            return redirect()->route('settings.id_design.index')
                           ->with('success', 'ID Card Design created successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded files on error using HelperClass
            if (isset($designFilePath)) {
                HelperClass::file_delete($designFilePath);
            }
            if (isset($previewImagePath)) {
                HelperClass::file_delete($previewImagePath);
            }

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

        if (!$design->fileExists()) {
            abort(404, 'Design file not found');
        }

        return response()->download(
            $design->getFullFilePath(),
            basename($design->file_path)
        );
    }
}
