<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\RoomResource;
use App\Http\Resources\TimeRangeResource;
use App\Http\Resources\UserResource;
use App\Models\Classes;
use App\Models\Client;
use App\Models\Record;
use App\Models\TimeRange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Database\Query\JoinClause;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request) {
        $user_id = $request->get('user_id', (\Session('user_id')) ? \Session('user_id') : 1);
        return Inertia::render('Records/RecordsIndex', [
            'user_id' => $user_id
        ]);
     }


    public function get_records(Request $request)
    {   
        $filters['education_date'] = $request->get('education_date', ((\Session('education_date')) ? \Session('education_date') : Date('Y-m-d'))); 
        $users = DB::table('users')->select(['id', 'name'])->whereIn('id', Record::where('educationDate', '=', $filters['education_date'])->get('user_id'))->orderBy('name')->get();   
        $filters['user_id'] = $request->get('user_id', (\Session('user_id')) ? \Session('user_id') : ((count($users) > 0) ? $users->first()->id : -1));  
        
        $records = DB::table('record')
                    ->select('record.start_time as start_time', 'record.end_time as end_time', 'clients.fio AS client_name', 'classes.name AS class_name', 'rooms.name as room_name', 'record.is_present', 'record.id', 'record.comment')
                    ->leftJoin('clients', 'record.client_id', '=', 'clients.id')
                    ->leftJoin('classes', 'record.class_id', '=', 'classes.id')
                    ->leftJoin('rooms', 'record.room_id', '=', 'rooms.id')
                    ->when($filters['education_date'], function ($query, $education_date) {
                            $query->where('record.educationDate', '=', $education_date);
                        })->when($request->get('user_id', $filters['user_id']), function ($query, $user_id) {
                            $query->where('record.user_id', '=', $user_id);
                        })
                    ->orderBy('record.start_time')
                    ->get();   
        \Session::put('education_date',$filters['education_date']);   
        \Session::put('user_id',$filters['user_id']);   
        return response()->json([
            'users' => $users,
            'records' => $records,
            'user_id' => $filters['user_id']
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
        $client_info = Client::where('id', $request->get('client_id'))->get()->load('wishes');
        return response()->json([
            'client_info' => $client_info
        ], 200);
    }

    public function getBusyTime(Request $request) {
        $busy_time = DB::table('record')->where('educationDate', $request->get('educationDate'))->where('user_id', $request->get('user_id'))->get(['start_time', 'end_time']);
        return response()->json([
            'busy_time' => $busy_time
        ], 200);
    }

    public function getFreeRooms(Request $request) {
        $start_time = $request->get('start_time_stamp');
        $end_time = $request->get('end_time_stamp');
        $educationDate = $request->get('educationDate');
       $free_rooms = RoomResource::collection(DB::select('SELECT id, name FROM rooms WHERE id NOT IN (
            SELECT room_id FROM record WHERE ("' . $start_time . '" > start_time AND "' . $start_time . '" < end_time)' .
            ' OR ("' . $end_time . '" > start_time AND "' . $end_time . '" < end_time)' .
            ' OR (start_time > "' . $start_time . '" AND start_time < "' . $end_time . '")' .
            ' AND educationDate="' . $educationDate .'") order by name'));
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
            'startTimeStamp' => 'required',
            'endTimeStamp' => 'required',
            'room_id' => 'required|integer',
            'class_id' => 'required|integer',
        ]);
        $record = Record::create([
            'client_id' => $request->get('client_id'),
            'educationDate' => $request->get('educationDate'),
            'user_id' => $request->get('user_id'),
            'start_time' => $request->get('startTimeStamp'),
            'end_time' => $request->get('endTimeStamp'),
            'room_id' => $request->get('room_id'),
            'class_id' => $request->get('class_id'),
        ]);
        return to_route('records.index', ['user_id' => $record->user_id]);
    }

    public function set_is_present(Request $request) {
        if (!$request->has('record_id')) { return response()->json('Нет record_id', 500); }
        $record = Record::firstWhere('id', $request->get('record_id'));
        $is_present = 1 - $record->is_present;
        $record->update(['is_present' => $is_present]);
        return response()->json(['is_present' => $is_present], 200);
    }

    public function store_record_comment(Request $request) {
        if (!$request->has('record_id')) { return response()->json('Нет record_id', 500); }
        $record = Record::firstWhere('id', $request->get('record_id'));
        if($request->has('comment')) { 
            $record->update(['comment' => $request->get('comment')]); 
        }
        return response()->json('Примечание сохранено', 200);
    }

    public function delete_record(Request $request) {
        if (!$request->has('record_id')) { return response()->json('Нет record_id', 500); }
        $record = Record::firstWhere('id', $request->get('record_id'));
        $record->delete();
        return response()->json('Запись успешно удалена', 200);
    }
}
