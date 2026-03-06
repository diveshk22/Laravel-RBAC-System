@extends('layout.app')

@section('content')

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: dark;
    margin: 0;
    padding: 0;
}

.projects-container {
    width: 95%;
    max-width: 1200px;
    margin: 40px auto;
}

.projects-title {
    text-align: center;
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 25px;
    color: white;
}

.projects-card {
    background: black;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    overflow: hidden;
    padding: 20px;
}

.projects-table {
    width: 100%;
    border-collapse: collapse;
}

.projects-table thead {
    background: linear-gradient(135deg, #4CAF50, #2ecc71);
    color: white;
}

.projects-table th {
    padding: 15px;
    text-align: left;
    font-size: 14px;
    font-weight: 500;
}

.projects-table td {
    padding: 14px 15px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    color: white;
}

.projects-table tbody tr:hover {
    background-color: green;
    transition: background 0.3s ease;
}

.badge-user {
    display: inline-block;
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    margin: 2px 2px;
}

.actions a,
.actions button {
    display: inline-block;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    margin-right: 6px;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}

.btn-view {
    background: #3498db;
    color: #fff;
}
.btn-view:hover {
    background: #2980b9;
}

.btn-add {
    background: #2ecc71;
    color: #fff;
}
.btn-add:hover {
    background: #27ae60;
}

.btn-edit {
    background: #f39c12;
    color: #fff;
}
.btn-edit:hover {
    background: #e67e22;
}

.btn-delete {
    background: #e74c3c;
    color: #fff;
}
.btn-delete:hover {
    background: #c0392b;
}

.no-data {
    text-align: center;
    padding: 20px;
    color: #888;
    font-style: italic;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .projects-table th, .projects-table td {
        padding: 10px;
        font-size: 13px;
    }

    .projects-title {
        font-size: 24px;
    }
}
</style>

<div class="projects-container">
    <form method="GET" action="{{ route('projects.index') }}" class="mb-4">
        <input type="text" name="search" placeholder="Search projects..." value="{{ request()->search }}" class="px-4 py-2 rounded-lg bg-gray-700 text-white focus:outline-none">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Search</button>
    </form>
    <div class="projects-title">All Projects</div>
        <div class="projects-card">
            <table class="projects-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Description</th>
                        <th>Assigned Users</th>
                        <th>Actions</th>
                    </tr>
                </thead>
<tbody>
    @forelse($projects as $key => $project)
        <tr>
            <td>{{ $projects->firstItem() + $key }}</td>
            <td><strong>{{ $project->name }}</strong></td>
            <td>{!! $project->description !!}</td>
            <td>
                @foreach($project->users as $user)
                    <span class="badge-user">{{ $user->name }}</span>
                @endforeach
            </td>
            <td class="actions">
                @php
                    $userRole = auth()->user()->getRoleNames()->first();
                @endphp

                <a href="{{ route('projects.tasks.index', $project->id) }}" class="btn-view">View Tasks</a>

                @if($userRole == 'admin' || $userRole == 'manager')
                    <a href="{{ route('projects.tasks.create', $project->id) }}" class="btn-add">Add Task</a>
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn-edit">Edit</a>

                    @if($userRole == 'admin')
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn-delete">
                                Delete
                            </button>
                        </form>
                    @endif
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="no-data">No Projects Found</td>
        </tr>
    @endforelse
</tbody>
</table>

{{-- Pagination Links Table ke bahar --}}
<div class="mt-4">
    {{ $projects->links() }}
</div>
</div>


@endsection