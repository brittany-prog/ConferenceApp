<?php

namespace App\Http\Controllers;

use App\Models\Day;

class DayController extends Controller
{
    public function index()
    {
        $days = Day::orderBy('sort_order')->get();
        return view('days.index', compact('days'));
    }
}
