<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Holiday Management';
        $section = 'Company Setup';
        $sub_section = 'Holidays';
        $query = Holiday::query();
        $searchTerm = $request->get('keyword');
        $searchableFields = ['title', 'start_date', 'end_date'];
        $holidays = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company_setup.holidays.search_results', compact('holidays'))->render();
        }
        return view('company_setup.holidays.index', compact('title', 'section', 'sub_section', 'holidays'));
    }

    public function create()
    {
        $title = 'Add Holiday';
        $section = 'Company Setup';
        $sub_section = 'Holidays';
        return view('company_setup.holidays.form', compact('title', 'section', 'sub_section'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'holidays' => 'required|array',
                'holidays.*.title' => 'required|string|max:255',
                'holidays.*.start_date' => 'required|date',
                'holidays.*.end_date' => 'nullable|date|after_or_equal:holidays.*.start_date',
                'holidays.*.status' => 'required|in:active,inactive',
            ],
            [
                'holidays.*.title.required' => 'Holiday title is required.',
                'holidays.*.start_date.required' => 'Start date is required.',
                'holidays.*.end_date.after_or_equal' => 'End date must be equal to or after start date.',
                'holidays.*.status.required' => 'Status is required.',
            ]
        );

        foreach ($request->holidays as $holidayData) {
            // If end_date is not provided, set it to start_date
            if (empty($holidayData['end_date'])) {
                $holidayData['end_date'] = $holidayData['start_date'];
            }
            Holiday::create($holidayData);
        }

        return redirect()->route('holidays.index')
            ->with([
                'message' => 'Holiday(s) created successfully',
                'alert-type' => 'success'
            ]);
    }

    public function edit($id)
    {
        $title = 'Edit Holiday';
        $section = 'Company Setup';
        $sub_section = 'Holidays';
        $holiday = Holiday::findOrFail($id);
        return view('company_setup.holidays.form', compact('title', 'section', 'sub_section', 'holiday'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate(
            [
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'required|in:active,inactive',
            ],
            [
                'title.required' => 'Holiday title is required.',
                'start_date.required' => 'Start date is required.',
                'end_date.after_or_equal' => 'End date must be equal to or after start date.',
                'status.required' => 'Status is required.',
            ]
        );

        // If end_date is not provided, set it to start_date
        if (empty($validatedData['end_date'])) {
            $validatedData['end_date'] = $validatedData['start_date'];
        }

        $holiday = Holiday::findOrFail($id);
        $holiday->update($validatedData);

        return redirect()->route('holidays.index')
            ->with([
                'message' => 'Holiday updated successfully',
                'alert-type' => 'success'
            ]);
    }

    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->route('holidays.index')
            ->with([
                'message' => 'Holiday deleted successfully',
                'alert-type' => 'success'
            ]);
    }

    public function calendar()
    {
        $title = 'Holiday Calendar';
        $section = 'Company Setup';
        $sub_section = 'Holidays';
        return view('company_setup.holidays.calendar', compact('title', 'section', 'sub_section'));
    }

    public function getHolidays()
    {
        $holidays = Holiday::where('status', 'active')->get();

        $events = [];
        foreach ($holidays as $holiday) {
            $events[] = [
                'title' => $holiday->title,
                'start' => $holiday->start_date->format('Y-m-d'),
                'end' => $holiday->end_date->addDay()->format('Y-m-d'), // FullCalendar end date is exclusive
                'color' => '#dc3545',
                'allDay' => true,
            ];
        }

        return response()->json($events);
    }
}
