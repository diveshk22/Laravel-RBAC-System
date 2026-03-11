<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssignedMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $task;

    // Constructor
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    // Common method to find task
    private function findTask($project_id_or_task_id, $id = null)
    {
        $task_id = $id ?? $project_id_or_task_id;
        return $this->task->findOrFail($task_id);
    }

    // Send mail function
    private function sendTaskMail($task)
    {
        $employee = User::find($task->assigned_to);

        if ($employee) {
            Mail::to($employee->email)->send(new TaskAssignedMail($task));
        }
    }

    // Show tasks of project
    public function index($project_id)
    {
        $project = Project::with('users')->findOrFail($project_id);
        $search = request()->search;

        $tasksQuery = $this->task->where('project_id', $project_id)
            ->with(['user', 'assignedUser'])
            ->latest();

        if ($search) {
            $tasksQuery->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $tasks = $tasksQuery->paginate(10)->withQueryString();

        return view('admin.Projects.Task.TaskIndex', compact('tasks', 'project', 'search'));
    }

    // Create task form
    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);
        $users = $project->users;

        return view('admin.Projects.Task.Createtask', compact('users', 'project_id'));
    }

    // Store task
    public function store(Request $request)
    {
        $request->validate([
            'project_id'   => 'required|exists:projects,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'assigned_to'  => 'required|exists:users,id',
            'due_date'     => 'nullable|date',
        ]);

        $task = $this->task->create([
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'user_id'     => auth()->id(),
            'project_id'  => $request->project_id,
            'status'      => 'Pending',
        ]);

        $this->sendTaskMail($task);

        return back()->with('success', 'Task created successfully!');
    }

    // Show task
    public function show($id)
    {
        $task = $this->task
            ->with(['project.users', 'user', 'assignedUser'])
            ->findOrFail($id);

        return view('admin.Projects.Task.TaskShow', compact('task'));
    }

    // Edit task form
    public function edit($project_id_or_task_id, $id = null)
    {
        $task = $this->findTask($project_id_or_task_id, $id);
        $users = $task->project->users;

        return view('admin.Projects.Task.edit-task', compact('task', 'users'));
    }

    // Update task
    public function update(Request $request, $project_id_or_task_id, $id = null)
    {
        $task = $this->findTask($project_id_or_task_id, $id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'status'      => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'status'      => $request->status,
            'assigned_to' => $request->assigned_to ?? $task->assigned_to,
        ]);

        return back()->with('success', 'Task updated successfully.');
    }

    // Delete task
    public function destroy($project_id_or_task_id, $id = null)
    {
        $task = $this->findTask($project_id_or_task_id, $id);
        $task->delete();

        return back()->with('success', 'Task Deleted Successfully');
    }

    // User tasks
    public function myTasks()
    {
        $tasks = $this->task
            ->where('assigned_to', auth()->id())
            ->with(['project', 'user'])
            ->latest()
            ->get();

        return view('User.mytasks', compact('tasks'));
    }

    // Add comment
    public function addComment(Request $request, $task_id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $task = $this->task->findOrFail($task_id);

        $task->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Comment added successfully!');
    }
}