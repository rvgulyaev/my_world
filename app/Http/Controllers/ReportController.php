<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\ClientSearchResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function get_clients_report(Request $request): Response {

        $client_id = $request->has('client_id') ? $request->get('client_id') : '';
        $start_date = $request->has('start_date') ? $request->get('start_date') : Date('Y-m-d', strtotime("-1 week"));
        $end_date = $request->has('end_date') ? $request->get('end_date') : Date('Y-m-d');
        $client = ClientResource::collection(
            Client::when($client_id, function ($query) use ($client_id){
                    return $query->where('id', $client_id);
            })->get()
        );
        return Inertia::render('Reports/ClientsReport', [
            'clients' => ClientSearchResource::collection(Client::all()),
        ]);
    }
}
