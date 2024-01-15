<?php

namespace App\Http\Controllers;

use App\Http\Resources\TimeRangeResource;
use App\Models\TimeRange;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TimeRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('TimeRanges/TimeRangesIndex', [
            'time_ranges' => TimeRangeResource::collection(TimeRange::all())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('TimeRanges/TimeRangesAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required|max:255|' . Rule::unique('time_ranges', 'name')
        ]);
        TimeRange::create([
            'name' => $request->name
        ]);
        return to_route('admin.time-ranges.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeRange $timeRange)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeRange $time_range): Response
    {
        return Inertia::render('TimeRanges/TimeRangesEdit', [
            'time_range' => $time_range
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimeRange $time_range)
    {
        $request->validate([
            'name' => 'string|required|max:255|' . Rule::unique('time_ranges')->ignore($time_range)
        ]);
        $time_range->update([
            'name' => $request->name,            
        ]);
        return to_route('admin.time-ranges.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeRange $timeRange)
    {
        $timeRange->delete();
        return to_route('admin.time-ranges.index');
    }
}
