<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\MovementTypeRequest;
use App\Models\Company\MovementType;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class MovementTypeController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Movement Types';
        $section = 'Company Info';
        $sub_section = 'Movement Types';

        if ($request->ajax()) {
            $query = MovementType::query();
            $movementTypes = $flexsearch->apply($query, [], $request->get('keyword'), ['name', 'description'])->orderBy('id', 'desc')->paginate(20);
            return view('company.movement_types.search_results', compact('movementTypes'))->render();
        }

        return view('company.movement_types.index', compact('title', 'section', 'sub_section'));
    }

    public function store(MovementTypeRequest $request)
    {
        try {
            $movementType = MovementType::create($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Movement Type saved successfully.',
                'data' => $movementType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save Movement Type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $movementType = MovementType::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $movementType
        ]);
    }

    public function update(MovementTypeRequest $request, $id)
    {
        try {
            $movementType = MovementType::findOrFail($id);
            $movementType->update($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Movement Type updated successfully.',
                'data' => $movementType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Movement Type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $movementType = MovementType::findOrFail($id);
            $movementType->delete();
            return response()->json([
                'success' => true,
                'message' => 'Movement Type deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Movement Type: ' . $e->getMessage()
            ], 500);
        }
    }
}
