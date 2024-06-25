<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class WeekReportExport implements FromView
{
    public function view(): View
    {
        $startDate = date('Y-m-d', strtotime("last monday midnight", strtotime("-1 week")));
        $endDate = date("Y-m-d", strtotime('next sunday', strtotime("-1 week")));
        $clients = DB::table('clients')->select(['clients.id', 'clients.fio'])->get();
        $classes = DB::table('classes')->select(['id as class_id', 'name as class_name'])->get();
        $clientdata = [];
        $weekdata = [];
        foreach($clients as $client) {
            foreach($classes as $class) {
                $wish = DB::table('wishes')->select(['prefer_amount_of_classes'])->where('class_id', '=', $class->class_id)->where('client_id', '=', $client->id)->first();
                $record = DB::table('record')->whereBetween('educationDate', [$startDate, $endDate])->where('client_id', '=', $client->id)->where('class_id', '=', $class->class_id)->where('is_present', '=', 1)->count();
                if (!is_null($wish)) {
                    array_push($clientdata, [$wish->prefer_amount_of_classes, $record, $wish->prefer_amount_of_classes - $record]);
                } else {
                    array_push($clientdata, [0,0,0]);
                }
                
            }
            array_push($weekdata, (object)['id' => $client->id, 'fio' => $client->fio, 'clientdata' => $clientdata]);
            $clientdata = [];
        }
        return view('exports.weekreport', ['startDate' => date('d.m.Y', strtotime($startDate)), 'endDate' => date('d.m.Y', strtotime($endDate)), 'classes' => $classes, 'weekdata' => $weekdata]);
    }
}
