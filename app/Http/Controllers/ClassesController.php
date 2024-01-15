<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Classes/ClassesIndex', [
            'classes' => ClassesResource::collection(Classes::all())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Classes/ClassesAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required|max:255|' . Rule::unique('classes', 'name')
        ]);
        Classes::create([
            'name' => $request->name
        ]);
        return to_route('admin.classes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classes $classes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $class_item): Response
    {
        return Inertia::render('Classes/ClassesEdit', [
            'class_item' => Classes::find($class_item)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $class_item)
    {
        $class = Classes::find($class_item);
        $request->validate([
            'name' => 'string|required|max:255|' . Rule::unique('classes')->ignore($class)
        ]);
        $class->update([
            'name' => $request->name
        ]);
        return to_route('admin.classes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $class_id)
    {
        Classes::find($class_id)->delete();
        return to_route('admin.classes.index');
    }
}
