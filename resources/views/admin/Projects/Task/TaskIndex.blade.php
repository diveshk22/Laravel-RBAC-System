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
    border-radius:12px;
    overflow:hidden;
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

.task-desc-container img {
    max-width:80px;
    height:auto;
    display:block;
    margin-top:5px;
    border-radius:5px;
}

.task-desc-container {
    font-size:12px;
    color:#94a3b8;
    max-height:100px;
    overflow:hidden;
}
</style>

<div class="task-container">

<form method="GET" action="{{ route('projects.tasks.index', $project->id) }}" class="mb-4">
    <input 
        type="text"
        name="search"
        placeholder="Search tasks..."
        value="{{ request()->search }}"
        class="px-4 py-2 rounded-lg bg-gray-700 text-white"
    >

    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
        Search
    </button>
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
            <th>Comments</th>
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
                    <strong>{{ $task->title }}</strong>
                </td>

                <td>
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

                    @unless(auth()->user()->hasRole('employee'))
                        <form 
                            action="{{ route('projects.tasks.destroy', [$project->id,$task->id]) }}"
                            method="POST"
                            style="display:inline;"
                        >
                            @csrf
                            @method('DELETE')

                            <button 
                                type="submit"
                                onclick="return confirm('Are you sure?')"
                                class="action-btn delete-btn"
                            >
                                Delete
                            </button>

                        </form>
                    @endunless

                </td>

                <td>

                    {{-- Show Comments --}}
                    @foreach($task->comments as $comment)

                        <div style="font-size:12px;margin-bottom:3px;">
                            <strong>{{ $comment->user->name }}</strong> :
                            {{ $comment->comment }}
                        </div>

                    @endforeach

                    {{-- Add Comment --}}
                    <form action="{{ route('tasks.comment',$task->id) }}" method="POST">
                        @csrf

                        <input
                            type="text"
                            name="comment"
                            placeholder="Write comment..."
                            style="width:100%;padding:4px;"
                        >

                        <button type="submit" class="action-btn">
                            Add Comment
                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="8" class="no-task">
                    No tasks found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

<div style="margin-top:25px; display:flex; justify-content:center;">
    {{ $tasks->links() }}
</div>

@endsection