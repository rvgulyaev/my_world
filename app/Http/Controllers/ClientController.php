<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\TimeRangeResource;
use App\Models\Classes;
use App\Models\Client;
use App\Models\TimeRange;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Clients/ClientsIndex', [
            'clients' => ClientResource::collection(Client::all()->sortBy('fio'))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Clients/ClientsAdd', [
            'classes' => ClassesResource::collection(Classes::all()),
            'timeRanges' => TimeRangeResource::collection(TimeRange::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fio' => 'required|string|max:255|' . Rule::unique('clients', 'fio'),
            'burndate' => 'required|date',
            'diagnos' => 'required|string|max:255',
            'contras' => 'required|string|max:255',
            'mother' => 'string|max:255',
            'mother_phone' => 'string|max:255',
            'father' => 'string|max:255',
            'father_phone' => 'string|max:255',
            'adress' => 'string|max:255',
            'wishes' => ['sometimes', 'array'],
        ]);
        $client = Client::create([
            'fio' => $request->fio,
            'burndate' => $request->burndate,
            'diagnos' => $request->diagnos,
            'contras' => $request->contras,
            'mother' => $request->mother,
            'mother_phone' => $request->mother_phone,
            'father' => $request->father,
            'father_phone' => $request->father_phone,
            'adress' => $request->adress,
        ]);
        if (isset($request->wishes) && count($request->wishes)>0) {
            foreach ($request->wishes as $wish) {
                Wish::create([
                    'class_id' => $wish->class_id,
                    'client_id' => $client->id,
                    'prefer_amount_of_classes' => $wish->prefer_amount_of_classes,
                    'prefer_time' => $wish->prefer_time
                ]);
            }
        }
        return to_route('clients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
