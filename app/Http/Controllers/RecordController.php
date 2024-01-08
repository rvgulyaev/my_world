<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\RecordResource;
use App\Http\Resources\RoomResource;
use App\Http\Resources\TimeRangeResource;
use App\Http\Resources\UserResource;
use App\Models\Classes;
use App\Models\Client;
use App\Models\Record;
use App\Models\TimeRange;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Filters\RecordFilter;
use Illuminate\Database\Query\JoinClause;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $filters['education_date'] = Request::input('education_date', Date('Y-m-d')); 
        $users = DB::table('users')->select(['id', 'name'])->whereIn('id', Record::where('educationDate', '=', $filters['education_date'])->get('id'))->orderBy('name')->get();      
        $filters['user_id'] = Request::input('user_id', $users->first() ? $users->first()->id : -1);  
        $filtered_records = Record::query()->when($filters['education_date'], function ($query, $education_date) {
            $query->where('educationDate', '=', $education_date);
        })->when(Request::input('user_id', $filters['user_id']), function ($query, $user_id) {
            $query->where('user_id', '=', $user_id);
        });
        $records = DB::table('time_ranges')
                    ->select('time_ranges.name AS time_range_name', 'clients.fio AS client_name', 'classes.name AS class_name', 'filtered_records.is_present')
                    ->leftJoinSub($filtered_records, 'filtered_records', function (JoinClause $join){
                        $join->on('time_ranges.id', '=', 'filtered_records.time_range');
                    })
                    ->leftJoin('clients', 'filtered_records.client_id', '=', 'clients.id')
                    ->leftJoin('classes', 'filtered_records.class_id', '=', 'classes.id')
                    ->orderBy('time_ranges.id')
                    ->get();      
        return Inertia::render('Records/RecordsIndex', [
            'users' => $users,
            'records' => $records,
            'filters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Records/RecordsAdd', [            
            'users' => UserResource::collection(User::where('specialist', 1)->get()),
            'clients' => ClientResource::collection(Client::all()),
            'classes' => ClassesResource::collection(Classes::all()),
            'time_ranges' => TimeRangeResource::collection(TimeRange::all()),
        ]);
    }

    public function getClientInfo(Request $request) {
        $client_info = Client::where('id', $request->client_id)->get()->load('wishes');
        return response()->json([
            'client_info' => $client_info
        ], 200);
    }

    public function getFreeTimeRanges(Request $request) {
        $free_time_ranges = TimeRangeResource::collection(DB::select('SELECT id, name FROM time_ranges WHERE id NOT IN (SELECT time_range FROM record WHERE user_id=' . $request->user_id . ' AND educationDate="' . $request->educationDate .'") order by id'));
        return response()->json([
            'free_time_ranges' => $free_time_ranges
        ], 200);
    }

    public function getFreeRooms(Request $request) {
        $free_rooms = RoomResource::collection(DB::select('SELECT id, name FROM rooms WHERE id NOT IN (SELECT room_id FROM record WHERE time_range=' . $request->time_range . ' AND educationDate="' . $request->educationDate .'") order by name'));
        return response()->json([
            'free_rooms' => $free_rooms
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer',
            'educationDate' => 'required|date',
            'user_id' => 'required|integer',
            'time_range' => 'required|integer',
            'room_id' => 'required|integer',
            'class_id' => 'required|integer',
        ]);
        $record = Record::create([
            'client_id' => $request->client_id,
            'educationDate' => $request->educationDate,
            'user_id' => $request->user_id,
            'time_range' => $request->time_range,
            'room_id' => $request->room_id,
            'class_id' => $request->class_id,
        ]);
        return to_route('records.index');
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
    public function update(Request $request, Record $record)
    {
        $is_present = 1 - $record->is_present;
        $record->update([
            'is_present' => $is_present,
        ]);
        return to_route('records.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
