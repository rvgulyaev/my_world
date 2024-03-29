<?php

namespace App\Http\Controllers;

use App\Exports\ClientsExport;
use App\Http\Resources\ClassesResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\TimeRangeResource;
use App\Models\Classes;
use App\Models\Client;
use App\Models\TimeRange;
use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = $request->has('search_client_fio') ? $request->input('search_client_fio') : '';
        $burndate = $request->boolean('search_near_burndate');
        $clients = ClientResource::collection(Client::when($search, function ($query) use ($search){
            return $query->where('fio', 'LIKE', '%'.$search.'%');
        })
        ->when($burndate, function($query) {
            return $query->whereRaw("DATE_FORMAT(`burndate`, '%m-%d') = DATE_FORMAT(NOW(), '%m-%d')");
        })->paginate(7));
        \Session::put('back_url',request()->fullUrl());
        return Inertia::render('Clients/ClientsIndex', [
            'clients' => $clients,
            'search_client' => $search,
            'search_near_burndate' => $burndate,
            'classes' => ClassesResource::collection(Classes::all())
        ]);
    }

    /**
    * Display a listing of the soft deleted items.
    */
    public function trashed(Request $request): Response
    {
     $search = $request->has('search_client_fio') ? $request->input('search_client_fio') : '';
     $clients = ClientResource::collection(Client::when($search, function ($query) use ($search){ 
            return $query->where('fio', 'LIKE', '%'.$search.'%');
        })->onlyTrashed()->paginate(7));
        \Session::put('back_url',request()->fullUrl());
        return Inertia::render('Clients/ClientsTrashed', [
             'clients' => $clients,
             'search_client' => $search
        ]);
    }
    
    public function restore(Request $request) {
     Client::onlyTrashed()->where('id', $request->client_id)->restore();
     return to_route('clients.trashed');
     }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Clients/ClientsAdd', [
            'classes' => ClassesResource::collection(Classes::all()),
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
            'mother' => 'max:255',
            'mother_phone' => 'max:255',
            'father' => 'max:255',
            'father_phone' => 'max:255',
            'adress' => 'max:255',
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
                    'class_id' => $wish['class_id'],
                    'client_id' => $client->id,
                    'prefer_amount_of_classes' => $wish['prefer_amount_of_classes'],
                    'prefer_day' => $wish['prefer_day'],
                    'prefer_time' => $wish['prefer_time']
                ]);
            }
        }
        if(\Session('back_url')){
            return redirect(\Session('back_url'));
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
    public function edit(int $id)
    {
        $client = Client::where('id', $id)->first();
        $client['wishes'] = Wish::where('client_id', $client->id)->get();
        return Inertia::render('Clients/ClientsEdit', [
            'client' => $client,
            'classes' => ClassesResource::collection(Classes::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'fio' => 'required|string|max:255|' . Rule::unique('clients')->ignore($client),
            'burndate' => 'required|date',
            'diagnos' => 'required|string|max:255',
            'contras' => 'required|string|max:255',
            'mother' => 'max:255',
            'mother_phone' => 'max:255',
            'father' => 'max:255',
            'father_phone' => 'max:255',
            'adress' => 'max:255',
            'wishes' => ['sometimes', 'array'],
        ]);

        $client->update([
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
        Wish::where('client_id', $client->id)->delete();
        if (isset($request->wishes) && count($request->wishes)>0) {
            foreach ($request->wishes as $wish) {
                Wish::create([
                    'class_id' => $wish['class_id'],
                    'client_id' => $client->id,
                    'prefer_amount_of_classes' => $wish['prefer_amount_of_classes'],
                    'prefer_day' => $wish['prefer_day'],
                    'prefer_time' => $wish['prefer_time'],
                ]);
            }
        }
        if(\Session('back_url')){
            return redirect(\Session('back_url'));
          }
        return to_route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::find($id);
        Wish::where('client_id', $id)->delete();
        $client->delete();
        if(\Session('back_url')){
            return redirect(\Session('back_url'));
          }
        return to_route('clients.index');
    }
    
    /**
     * Remove the specified resource from storage permanent.
     */
    public function terminate(Request $request)
    {
        Client::where('id', $request->client_id)->forceDelete();
        Wish::where('client_id', $request->client_id)->delete();
        if(\Session('back_url')){
            return redirect(\Session('back_url'));
          }
        return to_route('clients.trashed');
    }

    public function exportExcel()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }
}
