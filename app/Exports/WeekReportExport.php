<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromView;

class WeekReportExport implements FromView
{
    public function view(): View
    {
        $data = Storage::json('public/reports/weekreportdata.json');
        return view('exports.weekreport', ['startDate' => date('d.m.Y', strtotime($data['startDate'])), 'endDate' => date('d.m.Y', strtotime($data['endDate'])), 'classes' => $data['headclasses'], 'weekdata' => $data['weekdata']]);
    }
}
