<?php

namespace App\Http\Controllers;

use App\Models\Attandance;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class AttandancesController extends Controller
{
    public function index(FlexSearch $flexsearch, Request $request){
        $title = 'Employee Attendance';
        $section = 'Attendance';
        $sub_section = 'List';
        $query = Attandance::with('getEmployee');
        $searchableColumns = ['getEmployee.full_name', ];
        $keyword = $request->input('keyword');
        $filters = [];

        $attandances = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->paginate(10);
        if($request->ajax()){
            return view('attandances.search_results', compact('attandances'))->render();
        }
        return view('attandances.index', compact('attandances', 'title', 'section', 'sub_section'));
    }



}
