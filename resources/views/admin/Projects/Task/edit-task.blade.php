@extends('layout.app')

@section('content')

<style>
    body{
        background:#0f172a;
        font-family: 'Segoe UI', sans-serif;
    }

    .view-card{
        max-width: 750px;
        margin: 60px auto;
        padding: 40px;
        border-radius: 20px;
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(25px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.4);
        color: white;
    }

    .view-card h2{
        text-align: center;
        margin-bottom: 30px;
        font-size: 28px;
        font-weight: 700;
    }

    label{
        display:block;
        margin-top:18px;
        margin-bottom:8px;
        font-weight:600;
        color:#cbd5e1;
    }

    input, textarea, select{
        width:100%;
        padding:12px 14px;
        border-radius:10px;
        border:1px solid rgba(255,255,255,0.15);
        background: rgba(255,255,255,0.08);
        color:white;
        font-size:14px;
        outline:none;
    }

    select option{
        background:#0f172a;
        color:white;
    }
    
       body { background: #0f172a; font-family: 'Inter', sans-serif; }
    .container { display: flex; justify-content: center; align-items: center; margin-top: 60px; }
    .card { background: #020617; padding: 35px; width: 600px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.4); }
    .card h2 { text-align: center; margin-bottom: 25px; color: white; font-size: 28px; font-weight: 700; }
    .form-group { margin-bottom: 18px; }
    label { display: block; margin-bottom: 6px; font-weight: 600; color: #e2e8f0; }
    .form-control { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #475569; font-size: 14px; background: #0f172a; color: white; }
    
    /* FIX: Ensure CKEditor toolbar and text are visible and clear */
    .ck-editor__editable_inline {
        min-height: 250px;
        color: #333 !important; /* Text inside editor must be dark for visibility */
        background-color: white !important;
    }
    
    .ck.ck-editor__main>.ck-editor__editable {
        background: white !important;
    }

    /* FIX: Ensure bullets and numbers appear in the output */
    .ck-content ul, .ck-content ol {
        padding-left: 40px !important;
        margin: 1em 0 !important;
        list-style-type: revert !important;
    }
    .btn-update{
        margin-top:30px;
        width:100%;
        padding:14px;
        border:none;
        border-radius:12px;
        background: linear-gradient(135deg,#38bdf8,#0ea5e9);
        color:white;
        font-weight:700;
        cursor:pointer;
    }
</style>

<a href="{{ route('projects.tasks.index', $task->project_id) }}" style="color:white;">Back</a>

<div class="view-card">
    <h2>Edit Task</h2>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Task Title</label>
        <input type="text" name="title" value="{{ $task->title }}" required>

        <label>Description</label>
        <textarea name="description" id="editor" rows="6">{!! $task->description !!}</textarea>

        <label>Due Date</label>
        <input type="date" name="due_date" value="{{ $task->due_date }}">

        @php
            $user = auth()->user();
        @endphp

        @if($user->hasRole(['admin','super_admin','manager']))
        <label>Assign User</label>
        <select name="assigned_to">
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $task->assigned_to==$u->id?'selected':'' }}>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>
        @endif

        <label>Status</label>
        <select name="status">
            <option value="Pending" {{ $task->status=='Pending'?'selected':'' }}>Pending</option>
            <option value="In Progress" {{ $task->status=='In Progress'?'selected':'' }}>In Progress</option>
            <option value="Completed" {{ $task->status=='Completed'?'selected':'' }}>Completed</option>
        </select>

        <button type="submit" class="btn-update">Update Task</button>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [
                    'heading', '|', 
                    'bold', 'italic', 'link', '|', 
                    'bulletedList', 'numberedList', 'blockQuote', '|', 
                    'imageUpload', 'insertTable', 'undo', 'redo'
                ],
                // This enables the "Upload" tab and the toolbar button
                ckfinder: {
                    uploadUrl: "{{ route('projects.upload', ['_token' => csrf_token()]) }}"
                }
            })
            .then(editor => {
                console.log("Editor initialized with Image Upload");
            })
            .catch(error => {
                console.error("CKEditor Error:", error);
            });
    });
</script>
@endpush


@if(session('success'))

<script>

Swal.fire({
icon:'success',
title:'Success',
text:'{{ session('success') }}',
timer:3000,
showConfirmButton:false
})

</script>

@endif
@endsection 
