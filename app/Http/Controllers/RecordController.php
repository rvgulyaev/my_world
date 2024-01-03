<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecordResource;
use App\Http\Resources\RoomResource;
use App\Http\Resources\TimeRangeResource;
use App\Http\Resources\UserResource;
use App\Models\Record;
use App\Models\TimeRange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Records/RecordsIndex', [
            'records' => RecordResource::collection(Record::all()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Records/RecordsAdd', [            
            'users' => UserResource::collection(User::where('specialist', 1)->get()),
        ]);
    }

    public function getFreeTimeRanges(Request $request) {
        $free_time_ranges = TimeRangeResource::collection(DB::select('SELECT id, name FROM time_ranges WHERE id NOT IN (SELECT time_range FROM record WHERE user_id=' . $request->user_id . ' AND educationDate=' . $request->educationDate .') order by id'));
        return response()->json([
            'free_time_ranges' => $free_time_ranges
        ], 200);
    }

    public function getFreeRooms(Request $request) {
        $free_rooms = RoomResource::collection(DB::select('SELECT id, name FROM rooms WHERE id NOT IN (SELECT room_id FROM record WHERE time_range=' . $request->time_range . ' AND educationDate=' . $request->educationDate .') order by name'));
        return response()->json([
            'free_rooms' => $free_rooms
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
