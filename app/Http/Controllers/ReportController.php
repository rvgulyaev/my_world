<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\ClientSearchResource;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{

    public function clients_index() {
        return Inertia::render('Reports/ClientsReport');
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
                    ->leftJoin('classes', 'classes.id', '=', 'record.class_id')
                    ->whereBetween('record.educationDate', [$start_date, $end_date])
                    ->where('record.client_id', $client_id)
                    ->where('record.class_id', $record->class_id)
                    ->groupBy('record.educationDate')
                    ->get();
            if ($items) $record->records_by_date = $items;
        }
        
        return response()->json([
            'records' => $records
        ]);
    }

    public function get_clients() {
        $clients = DB::table('clients')->select(['id', 'fio as name'])->orderBy('fio')->get();
        return response()->json([
            'clients' => $clients
        ]);
    }

}
