@extends('layout.app')

@section('content')

<style>
    .project-form-wrapper {
        max-width: 700px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px 35px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        font-family: 'Segoe UI', sans-serif;
    }

    .project-form-wrapper h2 {
        text-align: center;
        margin-bottom: 25px;
        font-weight: 600;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #555;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: 0.3s ease;
    }

    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    select.form-control {
        height: 120px;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 6px;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: white;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.3s ease;
    }
    /* CKEditor Fix */

    .ck-editor__editable_inline {
        background: #1e293b !important;
        color: #ffffff !important;
        min-height: 180px;
    }

    .ck-editor__editable_inline p,
    .ck-editor__editable_inline span,
    .ck-editor__editable_inline div {
        color: #ffffff !important;
    }
    .submit-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .project-form-wrapper {
            margin: 20px;
            padding: 20px;
        }
    }
</style>
<div>
    <a href="{{ route('projects.index') }}" 
       class="btn btn-secondary mb-3">
        Back to Projects
    </a>
</div>


<div class="project-form-wrapper">
    <h2>Edit Project</h2>

<form action="{{ route('projects.update', ['project' => $project->id]) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Project Name:</label>
            <input type="text" name="name" 
                   value="{{ $project->name }}" 
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" id = "editor"
              class="form-control">{!!$project->description !!}</textarea>
        </div>

        <div class="form-group">
            <label>Assign Users (Hold Ctrl to select multiple):</label>
            <select name="users[]" multiple class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" 
                        {{ $project->users->contains($user->id) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="submit-btn">
            Update Project and Users
        </button>
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
    icon: 'success',
    title: 'Success',
    text: '{{ session('success') }}',
    confirmButtonColor: '#4f46e5'
});
</script>
@endif
@endsection
