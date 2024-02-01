<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = $request->has('search_task') ? $request->input('search_task') : null;
        if ($search !== null) {
            $tasks = TaskResource::collection(Task::where('task', 'LIKE', '%'.$search.'%')->get()->sortBy('executeDate'));
        } else {
            $tasks = TaskResource::collection(Task::all()->sortBy('executeDate'));
            $search = '';
        }
        return Inertia::render('Tasks/TasksIndex', [
            'tasks' => $tasks,
            'search_task' => $search
        ]);
    }

    /**
    * Display a listing of the soft deleted items.
    */
   public function trashed(Request $request): Response
   {
    $search = $request->has('search_task') ? $request->input('search_task') : null;
       if ($search !== null) {
        $tasks = TaskResource::collection(Task::onlyTrashed()->where('task', 'LIKE', '%'.$search.'%')->get()->sortBy('executeDate'));
       } else {
        $tasks = TaskResource::collection(Task::onlyTrashed()->get()->sortBy('executeDate'));
        $search = '';
       }
       return Inertia::render('Tasks/TasksTrashed', [
            'tasks' => $tasks,
            'search_task' => $search
       ]);
   }
   
   public function restore(Request $request) {
    Task::onlyTrashed()->where('id', $request->task_id)->restore();
    return to_route('tasks.trashed');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tasks/TasksAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255',
            'executeDate' => 'required|date',
        ]);
        $client = Task::create([
            'task' => $request->task,
            'executeDate' => $request->executeDate,
        ]);
        return to_route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'comments' => 'string|max:255|nullable'
        ]);

        $task->update([
            'comments' => $request->comments,
            'executed' => 1,
        ]);
        return to_route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        $task->delete();
        return to_route('tasks.index');
    }

    /**
     * Remove the specified resource from storage permanent.
     */
    public function terminate(Request $request)
    {
        Task::where('id', $request->task_id)->forceDelete();
        return to_route('tasks.trashed');
    }
}
