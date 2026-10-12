<?php

namespace App\Http\Controllers;

use App\Models\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        return view('timeline.index');
    }

    public function create()
    {
        return view('timeline.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'proposal_id' => 'required|exists:proposals,id',
            'activity' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Timeline::create($validatedData);

        return redirect()->route('timeline.index')->with('success', 'Timeline created successfully.');
    }
}
