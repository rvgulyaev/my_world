<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesSearchResource;
use App\Http\Resources\ClientSearchResource;
use App\Http\Resources\RecordEditResource;
use App\Http\Resources\RoomResource;
use App\Http\Resources\TimeRangeResource;
use App\Http\Resources\UserSearchResource;
use App\Http\Resources\WishResource;
use App\Models\Classes;
use App\Models\Client;
use App\Models\Record;
use App\Models\TimeRange;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request) {
        return Inertia::render('Records/RecordsIndex');
     }


    public function get_records(Request $request)
    {  
        $records = null; 
        ($request->has('education_date')) ? \Session::put('education_date',$request->get('education_date')) : ((!\Session::has('education_date') ? \Session::put('education_date', Date('Y-m-d')) : null));     
        $filters['education_date'] = \Session::get('education_date');
        if (auth()->user()->hasRole('user')) {
            $filters['user_id'] = auth()->user()->id;
        } else {
            ($request->has('user_id')) ? \Session::put('user_id',$request->get('user_id')) : ((!\Session::has('user_id') ? \Session::put('user_id', -1) : null));
            $filters['user_id'] = \Session::get('user_id');
        }
        $users = DB::table('users')->select(['id', 'name'])->whereIn('id', Record::where('educationDate', '=', \Session::get('education_date'))->get('user_id'))->orderBy('name')->get();
        if (count($users) > 0) {
            if (!auth()->user()->hasRole('user')) {
                $user_in_users = $users->filter(function($item) use ($filters) { return $item->id === $filters['user_id']; })->first();
                if (!$user_in_users) { \Session::put('user_id', $users->first()->id); }
                $filters['user_id'] = \Session::get('user_id'); 
            }
                    
            $records = DB::table('record')
                        ->select('record.start_time as start_time', 'record.end_time as end_time', 'clients.fio AS client_name', 'classes.name AS class_name', 'rooms.name as room_name', 'record.is_present', 'record.id', 'record.comment')
                        ->leftJoin('clients', 'record.client_id', '=', 'clients.id')
                        ->leftJoin('classes', 'record.class_id', '=', 'classes.id')
                        ->leftJoin('rooms', 'record.room_id', '=', 'rooms.id')
                        ->when($filters['education_date'], function ($query, $education_date) {
                                $query->where('record.educationDate', '=', $education_date);
                        })->when($filters['user_id'], function ($query, $user_id) {
                                $query->where('record.user_id', '=', $user_id);
                        })
                        ->orderBy('record.start_time')
                        ->get();   
         }
        return response()->json([
            'users' => $users,
            'records' => $records,
            'user_id' => $filters['user_id'],
            'education_date' => $filters['education_date']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Records/RecordsAdd', [            
            'users' => UserSearchResource::collection(User::where('specialist', 1)->select('name', 'id')->get()->sortBy('name')),
            'clients' => ClientSearchResource::collection(Client::select('fio', 'id')->get()->sortBy('fio')),
            'classes' => ClassesSearchResource::collection(Classes::select('name', 'id')->get()->sortBy('name')),
            'time_ranges' => TimeRangeResource::collection(TimeRange::all()),
        ]);
    }

    public function edit(Record $record): Response {
        return Inertia::render('Records/RecordsEdit', [   
            'record' => new RecordEditResource($record),
            'client_info' => $this->clientInfo($record->client_id),       
            'wishes' => $this->clientWishes($record->client_id),       
            'users' => UserSearchResource::collection(User::where('specialist', 1)->select('name', 'id')->get()->sortBy('name')),
            'clients' => ClientSearchResource::collection(Client::select('fio', 'id')->get()->sortBy('fio')),
            'classes' => ClassesSearchResource::collection(Classes::select('name', 'id')->get()->sortBy('name')),
        ]);
    }

    private function clientInfo(int $id) {
        return DB::table('clients')->where('id', $id)->first();
    }

    private function clientWishes(int $id) {
        return WishResource::collection(Wish::where('client_id', $id)->get());
    }

    public function getClientInfo(Request $request) {
        $client_id = $request->get('client_id');
        return response()->json([
            'client_info' => $this->clientInfo($client_id),
            'wishes' => $this->clientWishes($client_id)
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
            ' OR (start_time = "' . $start_time . '" AND end_time = "' . $end_time . '")' .
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
            'client_id' => 'required|numeric|min:1',
            'educationDate' => 'required|date',
            'user_id' => 'required|numeric|min:1',
            'startTimeStamp' => 'required',
            'endTimeStamp' => 'required',
            'room_id' => 'required|numeric|min:1',
            'class_id' => 'required|numeric|min:1',
        ], [
            'client_id.min' => 'Поле не может быть пустым.',
            'user_id.min' => 'Поле не может быть пустым.',
            'room_id.min' => 'Поле не может быть пустым.',
            'class_id.min' => 'Поле не может быть пустым.',
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

    public function update(Request $request, Record $record) {
        $request->validate([
            'client_id' => 'required|numeric|min:1',
            'educationDate' => 'required|date',
            'user_id' => 'required|numeric|min:1',
            'startTimeStamp' => 'required',
            'endTimeStamp' => 'required',
            'room_id' => 'required|numeric|min:1',
            'class_id' => 'required|numeric|min:1',
        ], [
            'client_id.min' => 'Поле не может быть пустым.',
            'user_id.min' => 'Поле не может быть пустым.',
            'room_id.min' => 'Поле не может быть пустым.',
            'class_id.min' => 'Поле не может быть пустым.',
        ]);

        $record->update([
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
