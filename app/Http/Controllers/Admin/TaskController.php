<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Show tasks of specific project
    public function index($project_id)
    {
        $project = Project::with('users')->findOrFail($project_id);
        $search = request()->search;
        // Base Query (get() nahi lagana)
        $tasksQuery = Task::where('project_id', $project_id)
            ->with(['user', 'assignedUser'])
            ->latest();

        // Search filter directly query par lagao
        if (!empty($search)) {
            $tasksQuery->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Yaha paginate karo (get() nahi lagana)
        $tasks = $tasksQuery->paginate(10)->withQueryString();

        return view('admin.Projects.Task.TaskIndex', compact('tasks', 'project'));
    }

    // Show create task form
    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);

        // Only users assigned to this project
        $users = $project->users;

        return view('admin.Projects.Task.Createtask', compact('users', 'project_id'));
    }

    // Store task
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'user_id' => auth()->id(),
            'project_id' => $request->project_id,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Task created successfully!');
    }

    // Show single task
    public function show($id)
    {   
        // dd(auth()->user()->getRoleNames());
        $task = Task::with(['project.users', 'user', 'assignedUser'])->findOrFail($id);
        return view('admin.Projects.Task.TaskShow', compact('task'));
    }

    // Show edit task form
    public function edit($project_id_or_task_id, $id = null)
    {
        // If called with 2 params (nested route), use $id. Otherwise use first param.
        $task_id = $id ?? $project_id_or_task_id;
        $task = Task::with(['project'])->findOrFail($task_id);
        $users = $task->project->users;
        
        return view('admin.Projects.Task.edit-task', compact('task', 'users'));
    }

    // Edit task update
    public function update(Request $request, $project_id_or_task_id, $id = null)
    {
    
        //dd($request->all());
    
        //If called with 2 params (nested route), use $id. Otherwise use first param.
        $task_id = $id ?? $project_id_or_task_id;
        $task = Task::findOrFail($task_id);
        //dd($task->fresh());

        // $validated = $request->validate($rules);

        // dd($validated);
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'status'      => 'required|in:Pending,In Progress,Completed',
        ]);

        $updateData = [
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'status'      => $request->status,
        ];

        if ($request->filled('assigned_to')) {
        $updateData['assigned_to'] = $request->assigned_to;
        }

        $task->update($updateData);

        return back()->with('success', 'Task updated successfully.');
    }



    // Delete task
    public function destroy($project_id_or_task_id, $id = null)
    {
        // If called with 2 params (nested route), use $id. Otherwise use first param.
        $task_id = $id ?? $project_id_or_task_id;
        Task::findOrFail($task_id)->delete();

        return back()->with('success', 'Task Deleted Successfully');
    }

    // User: View all my tasks
    public function myTasks()
    {
        $tasks = Task::where('assigned_to', auth()->id())
            ->with(['project', 'user'])
            ->latest()
            ->get();

        return view('User.mytasks', compact('tasks'));
    }
}
