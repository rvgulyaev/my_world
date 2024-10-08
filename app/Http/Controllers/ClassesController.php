<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesGroupResource;
use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\ClassesGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'classes' => ClassesResource::collection(Classes::all()->sortBy('order'))
        ]);
    }

    /**
    * Display a listing of the soft deleted items.
    */
    public function trashed(): Response
    {
        return Inertia::render('Classes/ClassesTrashed', [
             'classes' => ClassesResource::collection(Classes::onlyTrashed()->get()),
        ]);
    }
    
    public function restore(Request $request) {
     Classes::onlyTrashed()->where('id', $request->class_id)->restore();
     return to_route('admin.classes.trashed');
     }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $classes_groups = ClassesGroupResource::collection(ClassesGroups::all());
        return Inertia::render('Classes/ClassesAdd', ['classes_groups' => $classes_groups]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required|max:255|' . Rule::unique('classes', 'name'),
        ]);
        Classes::create([
            'name' => $request->name,
            'has_group' => $request->has_group,
            'class_group_id' => $request->class_group_id['id'],
            'order' => $request->order
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
        $groups = collect(DB::table('classes_groups')->select('id', 'name')->get()->toArray());
        $groups->push((object)['id' => 0, 'name' => 'Вне группы']);
        $classes_groups = collect($groups);
        return Inertia::render('Classes/ClassesEdit', [
            'class_item' => Classes::find($class_item),
            'classes_groups' => $classes_groups
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
            'name' => $request->name,
            'has_group' => $request->has_group,
            'class_group_id' => $request->class_group_id['id'],
            'order' => $request->order
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
    
    /**
     * Remove the specified resource from storage permanent.
     */
    public function terminate(Request $request)
    {
        Classes::where('id', $request->class_id)->forceDelete();
        return to_route('admin.classes.trashed');
    }
}
