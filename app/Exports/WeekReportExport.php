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
        $clients = DB::table('clients')->select(['clients.id', 'clients.fio'])->whereNull('clients.deleted_at')->get()->sortBy('clients.fio');
        $classes = DB::table('classes')->select(['classes.id as class_id', 'classes.name as class_name', 'classes.class_group_id', 'classes_groups.name as group_name'])->leftJoin('classes_groups', 'classes_groups.id', '=', 'classes.class_group_id')->whereNull('classes.deleted_at')->get()->sortBy('class_group_id');
        
     
        $clientdata = [];
        $headclasses = [];
        $weekdata = collect();
        
        $lastgroup = 0;
        foreach($classes as $class) {
            if($class->class_group_id === 0) {
                array_push($headclasses, ['class_id' => $class->class_id, 'class_name' => $class->class_name, 'group_id' => $class->class_group_id]);
            } else {
                if($lastgroup !== $class->class_group_id) {
                    array_push($headclasses, ['class_id' => $class->class_id, 'class_name' => $class->group_name, 'group_id' => $class->class_group_id]);
                    $lastgroup = $class->class_group_id;
                }
            }
        }

        $lastgroup = 0;
        foreach($clients as $client) {
            foreach($headclasses as $class) {

                if ($class['group_id'] !== 0) {
                    $record = DB::table('record')->leftJoin('classes', 'classes.id', '=', 'record.class_id')->leftJoin('classes_groups', 'classes_groups.id', '=', 'classes.class_group_id')->where('client_id', '=', $client->id)->whereBetween('educationDate', [$startDate, $endDate])
                    ->where('classes.class_group_id', '=', $class['group_id'])->where('is_present', '=', 1)->count();
                    array_push($clientdata, $record);
                } else {
                    $record = DB::table('record')->leftJoin('classes', 'classes.id', '=', 'record.class_id')->where('client_id', '=', $client->id)->whereBetween('educationDate', [$startDate, $endDate])
                    ->where('record.class_id', '=', $class['class_id'])->where('is_present', '=', 1)->count();
                    array_push($clientdata, $record);
                }
                              
            }
            $weekdata->push(['id' => $client->id, 'fio' => $client->fio, 'clientdata' => $clientdata]);
            $clientdata = [];
        }

        return view('exports.weekreport', ['startDate' => date('d.m.Y', strtotime($startDate)), 'endDate' => date('d.m.Y', strtotime($endDate)), 'classes' => $headclasses, 'weekdata' => $weekdata]);
    }
}
