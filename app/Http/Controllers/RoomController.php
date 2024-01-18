<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Rooms/RoomsIndex',
        [
            'rooms' => RoomResource::collection(Room::all())
        ]
        );
    }

    /**
    * Display a listing of the soft deleted items.
    */
    public function trashed(): Response
    {
        return Inertia::render('Rooms/RoomsTrashed', [
             'rooms' => RoomResource::collection(Room::onlyTrashed()->get()),
        ]);
    }
    
    public function restore(Request $request) {
     Room::onlyTrashed()->where('id', $request->room_id)->restore();
     return to_route('admin.rooms.trashed');
     }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Rooms/RoomsAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|' . Rule::unique('rooms', 'name'),
        ]);        

        Room::create([
            'name' => $request->name,
        ]);
        return to_route('admin.rooms.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room): Response
    {
        return Inertia::render('Rooms/RoomsEdit', 
        [
            'room' => $room
        ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255|' . Rule::unique('rooms')->ignore($room),
        ]);

        $room->update([
            'name' => $request->name,
        ]);
        return to_route('admin.rooms.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return to_route('admin.rooms.index');
    }
     
    /**
     * Remove the specified resource from storage permanent.
     */
    public function terminate(Request $request)
    {
        Room::where('id', $request->room_id)->forceDelete();
        return to_route('admin.rooms.trashed');
    }
}
