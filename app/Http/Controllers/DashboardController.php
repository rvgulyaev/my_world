<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        $clients = ClientResource::collection(Client::whereRaw('DATE_FORMAT(`burndate`, "%m-%d") = DATE_FORMAT(NOW(), "%m-%d")')->get());

        return Inertia::render('Dashboard', [
            'clients' => $clients
        ]);
    }
}
