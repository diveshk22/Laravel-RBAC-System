@extends('layout.app')
@section('content')

<style>
body{
    margin:0;
    font-family:'Inter', sans-serif;
    background:linear-gradient(135deg,#0f172a,#1e293b);
    color:#e5e7eb;
}

.task-container{
    max-width:1200px;
    margin:50px auto;
    padding:40px;
    border-radius:20px;
    background:rgba(255,255,255,0.05);
    backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,0.08);
    box-shadow:0 20px 60px rgba(0,0,0,0.5);
}

.task-title{
    text-align:center;
    font-size:28px;
    font-weight:700;
    margin-bottom:30px;
}

.task-table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:12px;
}

.task-table thead{
    background:#1e293b;
}

.task-table th{
    padding:14px;
    text-align:left;
    font-size:14px;
    font-weight:600;
    color:#93c5fd;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

.task-table td{
    padding:14px;
    font-size:14px;
    border-bottom:1px solid rgba(255,255,255,0.05);
}

.task-table tbody tr:hover{
    background:rgba(255,255,255,0.04);
    transition:0.3s;
}

/* Status Badges */
.badge{
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.pending{ background:#f59e0b; color:#111; }
.progress{ background:#3b82f6; }
.completed{ background:#22c55e; color:#111; }
.rejected{ background:#ef4444; }

/* Buttons */
.action-btn{
    padding:6px 12px;
    border-radius:6px;
    font-size:13px;
    text-decoration:none;
    color:white;
    border:none;
    cursor:pointer;
    margin-right:5px;
}

.edit-btn{ background:#3b82f6; }
.delete-btn{ background:#ef4444; }

.edit-btn:hover{ background:#2563eb; }
.delete-btn:hover{ background:#dc2626; }

.back-link{
    display:inline-block;
    margin-bottom:20px;
    text-decoration:none;
    color:#93c5fd;
}

.no-task{
    text-align:center;
    padding:20px;
    color:#94a3b8;
}

@media(max-width:768px){
    .task-container{ padding:20px; }
    .task-table{ font-size:12px; }
}

.task-desc-container img {
    max-width: 80px; /* Limits the width so it stays small in the row */
    height: auto;
    display: block;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid rgba(255,255,255,0.1);
}

.task-desc-container {
    font-size: 12px;
    color: #94a3b8;
    max-height: 100px; /* Prevents long descriptions from stretching the row too much */
    overflow: hidden;
}
</style>

<div class="task-container">
<form method="GET" action="{{ route('projects.tasks.index', $project->id) }}" class="mb-4">
    <input type="text" name="search" placeholder="Search tasks..." value="{{ request()->search }}" class="px-4 py-2 rounded-lg bg-gray-700 text-white focus:outline-none">
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Search</button>
</form>

<a href="{{ route('projects.index') }}">Back</a>

    <div class="task-title">📋 All Tasks</div>

            <table class="task-table">

            <thead>
            <tr>
                <th>#</th>
                <th>Task Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($tasks as $task)

            @php
            $class = match(strtolower($task->status)){
                'pending' => 'pending',
                'in progress' => 'progress',
                'completed' => 'completed',
                'rejected' => 'rejected',
                default => 'pending'
            };
            @endphp

            <tr>

            <td>{{ $loop->iteration }}</td>

            <td>
            <strong>{{ $task->title }}</strong><br>

                <td>
                <strong>{{ $task->title }}</strong><br>

                <div class="task-desc-container">
                {!! $task->description !!}
                </div>

                </td>

            <td>
            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '—' }}
            </td>

            <td>
            {{ $task->assignedUser->name ?? 'Not Assigned' }}
            </td>

            <td>
            <span class="badge {{ $class }}">
            {{ $task->status }}
            </span>
            </td>

            <td>

            <a href="{{ route('projects.tasks.edit', [$project->id,$task->id]) }}" class="action-btn edit-btn">
            Edit
            </a>

            @unless(auth()->user()->hasRole('user'))
            <form action="{{ route('projects.tasks.destroy', [$project->id,$task->id]) }}"
            method="POST"
            style="display:inline;">

            @csrf
            @method('DELETE')

            <button type="submit"
            onclick="return confirm('Are you sure?')"
            class="action-btn delete-btn">
            Delete
            </button>

            </form>
            @endunless

            </td>

            </tr>

            @empty

            <tr>
            <td colspan="6" class="no-task">
            No tasks found.
            </td>
            </tr>

            @endforelse
            </tbody>

            </table>
            {{-- Pagination Links --}}
        <div style="margin-top:25px; display:flex; justify-content:center;">
            {{ $tasks->links() }}
        </div>

</div>

@endsection
    