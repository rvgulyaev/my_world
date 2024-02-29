<?php

namespace App\Exports;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Wish;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClientsExport implements FromView
{
    public function view(): View
    {
        $result = [];
        $clients = Client::all();
        foreach($clients as $client) {
            $wishes = DB::table('wishes as w')->select(['cl.name as clas', 'w.prefer_amount_of_classes', 'w.prefer_day', 'w.prefer_time'])->leftJoin('classes as cl', 'w.class_id', '=', 'cl.id')->where('w.client_id', $client->id)->get();
            $client['wishes'] = $wishes;
            array_push($result, $client);
        }
        return view('exports.clientexport', ['clients' => $result]);
    }
}
