@extends('layout.app')

@section('content')

<style>
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

    .btn-submit { width: 100%; padding: 12px; background: #22c55e; border: none; color: white; font-size: 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
    .btn-submit:hover { background: #16a34a; }
</style>

<div class="container">
    <div class="card">
        <h2>Create Project</h2>
        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Project Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter project name" required>
            </div>

            <div class="form-group">
                <label>Project Description</label>
                <div class="ck-content">
                    <textarea name="description" id="editor"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label>Assign To</label>
                <select name="users[]" class="form-control" multiple required style="height: 120px;">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-submit">Create Project</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
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
        timer: 3000,
        showConfirmButton: false
    })
</script>
@endif
@endpush