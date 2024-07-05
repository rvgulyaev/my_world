<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesGroupResource;
use App\Models\ClassesGroups;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ClassesGroupController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Classes_groups/ClassesGroupsIndex', [
            'classes_groups' => ClassesGroupResource::collection(ClassesGroups::all())
        ]);
    }

    /**
    * Display a listing of the soft deleted items.
    */
    public function trashed(): Response
    {
        return Inertia::render('Classes_groups/ClassesGroupsTrashed', [
             'classes_groups' => ClassesGroupResource::collection(ClassesGroups::onlyTrashed()->get()),
        ]);
    }
    
    public function restore(Request $request) {
     ClassesGroups::onlyTrashed()->where('id', $request->class_group_id)->restore();
     return to_route('admin.classes_groups.trashed');
     }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Classes_groups/ClassesGroupsAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required|max:255|' . Rule::unique('classes_groups', 'name')
        ]);
        ClassesGroups::create([
            'name' => $request->name
        ]);
        return to_route('admin.classes_groups.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassesGroups $classes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $class_group_item): Response
    {
        return Inertia::render('Classes_groups/ClassesGroupsEdit', [
            'class_group_item' => ClassesGroups::find($class_group_item)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $class_group_item)
    {
        $class_group = ClassesGroups::find($class_group_item);
        $request->validate([
            'name' => 'string|required|max:255|' . Rule::unique('classes_groups')->ignore($class_group)
        ]);
        $class_group->update([
            'name' => $request->name
        ]);
        return to_route('admin.classes_groups.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $class_group_id)
    {
        ClassesGroups::find($class_group_id)->delete();
        return to_route('admin.classes_groups.index');
    }
    
    /**
     * Remove the specified resource from storage permanent.
     */
    public function terminate(Request $request)
    {
        ClassesGroups::where('id', $request->class_group_id)->forceDelete();
        return to_route('admin.classes_groups.trashed');
    }
}
