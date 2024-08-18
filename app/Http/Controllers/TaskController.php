<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function view()
    {
        return view('tasks');
    }

    public function fetchTask()
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();
        return response()->json($tasks);
    }

 
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tasks,name'
        ]);

        $task = Task::create([
            'name' => $request->name,
            'completed' => false
        ]);

        return response()->json($task);
    }


    public function update(Request $request, Task $task)
    {
  
        $validatedData = $request->validate([
            'completed' => 'required|boolean',
        ]);

        $task->update($validatedData);
        return response()->json(['task' => $task], 200);
    }


    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
     }
}
