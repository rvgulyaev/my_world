<?php

namespace App\Http\Controllers;

use App\Exports\WeekReportExport;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ClientSearchResource;
use App\Http\Resources\UserResource;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function clients_index() {
        return Inertia::render('Reports/ClientsReport');
    }
    public function specialists_index() {
        return Inertia::render('Reports/SpecialistsReport');
    }
    public function weekreport_index() {
        $startDate = date('Y-m-d', strtotime("last sunday midnight", strtotime("-1 week +1 day")));
        $endDate = date("Y-m-d", strtotime('next saturday', strtotime("-1 week +1 day")));
        $clients = DB::table('clients')->select(['clients.id', 'clients.fio'])->get();
        $classes = DB::table('classes')->select(['classes.id as class_id', 'classes.name as class_name', 'classes.class_group_id', 'classes_groups.name as group_name'])->leftJoin('classes_groups', 'classes_groups.id', '=', 'classes.class_group_id')->get()->sortBy('class_group_id');
        
        $records = 0;
        $clientdata = [];
        $headclasses = [];
        $weekdata = collect();
        
        $lastgroup = 0;
        foreach($classes as $class) {
            if($class->class_group_id === 0) {
                array_push($headclasses, ['class_id' => $class->class_id, 'class_name' => $class->class_name, 'group_id' => $class->class_group_id]);
            } else {
                if($lastgroup !== $class->class_group_id) {
                    array_push($headclasses, ['class_id' => $class->class_group_id, 'class_name' => $class->group_name, 'group_id' => $class->class_group_id]);
                    $lastgroup = $class->class_group_id;
                }
            }
        }

        $lastgroup = 0;
        foreach($clients as $client) {
            foreach($headclasses as $class) { 
                    $record = DB::table('record')->whereBetween('educationDate', [$startDate, $endDate])
                    ->leftJoin('classes', 'classes.id', '=', 'record.class_id')->where('client_id', '=', $client->id)->where('classes.class_group_id', '=', $class['group_id'])->where('is_present', '=', 1)->count();
                         
                if ($lastgroup === $class['group_id'] && $class['group_id'] !== 0) {
                    $records = $records + $record;
                } else {
                    array_push($clientdata, $records);
                    $lastgroup = $class['group_id'];
                    $records = $record;
                }
                              
            }
            $weekdata->push(['id' => $client->id, 'fio' => $client->fio, 'clientdata' => $clientdata]);
            $clientdata = [];
        }
        return Inertia::render('Reports/WeekReport', ['classes' => $headclasses, 'weekdata' => $weekdata]);
    }
    public function get_clients_report(Request $request) {
        $clients = ClientSearchResource::collection(Client::all());
        $client_id = $request->has('client_id') ? $request->get('client_id') : $clients[0]->id;
        $start_date = $request->has('start_date') ? $request->get('start_date') : Date('Y-m-d', strtotime("-1 week"));
        $end_date = $request->has('end_date') ? $request->get('end_date') : Date('Y-m-d');
        $client = ClientResource::collection(
            Client::when($client_id, function ($query) use ($client_id){
                    return $query->where('id', $client_id);
            })->get()
        );
        $records = DB::table('classes', 'cls')
                    ->select(['cls.id as class_id', 'cls.name as class_name'])
                    ->selectSub("SELECT COUNT(id) FROM record AS r
                    WHERE r.educationDate BETWEEN '" . $start_date . "' AND '" . $end_date . "'
                    AND r.client_id = " . $client_id . "
                    AND r.class_id = cls.id", "sum_count")
                    ->selectSub("SELECT COUNT(id) FROM record AS r
                    WHERE r.educationDate BETWEEN '" . $start_date . "' AND '" . $end_date . "'
                    AND r.client_id = " . $client_id . "
                    AND r.class_id = cls.id
                    AND r.is_present = 1", "present")
                    ->selectSub("SELECT COUNT(id) FROM record AS r
                    WHERE r.educationDate BETWEEN '" . $start_date . "' AND '" . $end_date . "'
                    AND r.client_id = " . $client_id . "
                    AND r.class_id = cls.id
                    AND r.is_present = 0", "not_present")
                    ->leftJoin('record', 'record.class_id', '=', 'cls.id')
                    ->whereBetween('record.educationDate', [$start_date, $end_date] )
                    ->where('record.client_id', $client_id)
                    ->groupBy('cls.id')
                    ->get();
        foreach ($records as $record) {
            $items = DB::table('record')->select(['record.educationDate', DB::raw('COUNT(record.id) as date_count')])
                    ->whereBetween('record.educationDate', [$start_date, $end_date])
                    ->where('record.client_id', $client_id)
                    ->where('record.class_id', $record->class_id)
                    ->groupBy('record.educationDate')
                    ->get();
            if ($items) $record->records_by_date = $items;
        }

        return response()->json([
            'records' => $records,
            'client' => $client
        ]);
    }
    public function get_specialists_report(Request $request) {
        $specialists = UserResource::collection(User::where('specialist', '=', 1)->get());
        $specialist_id = $request->has('specialist_id') ? $request->get('specialist_id') : $specialists[0]->id;
        $start_date = $request->has('start_date') ? $request->get('start_date') : Date('Y-m-d', strtotime("-1 week"));
        $end_date = $request->has('end_date') ? $request->get('end_date') : Date('Y-m-d');
        $records = DB::table('classes', 'cls')
                    ->select(['cls.id as class_id', 'cls.name as class_name'])
                    ->selectSub("SELECT COUNT(id) FROM record AS r
                    WHERE r.educationDate BETWEEN '" . $start_date . "' AND '" . $end_date . "'
                    AND r.user_id = " . $specialist_id . "
                    AND r.class_id = cls.id", "sum_count")
                    ->selectSub("SELECT COUNT(id) FROM record AS r
                    WHERE r.educationDate BETWEEN '" . $start_date . "' AND '" . $end_date . "'
                    AND r.user_id = " . $specialist_id . "
                    AND r.class_id = cls.id
                    AND r.is_present = 1", "present")
                    ->selectSub("SELECT COUNT(id) FROM record AS r
                    WHERE r.educationDate BETWEEN '" . $start_date . "' AND '" . $end_date . "'
                    AND r.user_id = " . $specialist_id . "
                    AND r.class_id = cls.id
                    AND r.is_present = 0", "not_present")
                    ->leftJoin('record', 'record.class_id', '=', 'cls.id')
                    ->whereBetween('record.educationDate', [$start_date, $end_date] )
                    ->where('record.user_id', $specialist_id)
                    ->groupBy('cls.id')
                    ->get();

        return response()->json([
            'records' => $records,
        ]);
    }

    public function get_clients() {
        $clients = DB::table('clients')->select(['id', 'fio as name'])->orderBy('fio')->get();
        return response()->json([
            'clients' => $clients
        ]);
    }
    public function get_specialists() {
        $specialists = DB::table('users')->select(['id', 'name'])->orderBy('name')->get();
        return response()->json([
            'specialists' => $specialists
        ]);
    }

    public function exportExcel()
    {
        $startDate = date('d.m.Y', strtotime("last monday midnight", strtotime("-1 week")));
        $endDate = date("d.m.Y", strtotime('next sunday', strtotime("-1 week")));
        return Excel::download(new WeekReportExport, 'WeekReport - ' . $startDate . ' - ' . $endDate . '.xlsx');
    }

}
