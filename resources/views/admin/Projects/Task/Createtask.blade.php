    @extends('layout.app')

    @section('content')

    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        body{
            background: radial-gradient(circle at top, #0b1220, #0f172a 60%);
            font-family: 'Inter', sans-serif;
        }

        .task-card{
            max-width: 760px;
            margin: 70px auto;
            padding: 45px;
            border-radius: 24px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(35px);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 30px 80px rgba(0,0,0,0.55);
            color: #fff;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn{
            from{opacity:0; transform: translateY(20px);}
            to{opacity:1; transform: translateY(0);}
        }

        .task-card h2{
            text-align: center;
            margin-bottom: 35px;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: 1px;
            background: linear-gradient(90deg,#38bdf8,#6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-group{
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
        }

        .form-group label{
            margin-bottom: 10px;
            font-size: 13px;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea{
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.04);
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: all 0.25s ease;
        }

        .form-group textarea{
            resize: none;
            min-height: 150px;
        }

        .form-group select option{
            background: #0f172a;
            color: white;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus{
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56,189,248,0.25);
            background: rgba(255,255,255,0.07);
        }

        .submit-btn{
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #38bdf8, #6366f1);
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .submit-btn:hover{
            transform: translateY(-3px);
            box-shadow: 0 18px 40px rgba(56,189,248,0.45);
        }

        .error-box{
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.4);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            color: #fca5a5;
            font-size: 14px;
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
    </style>

    <a href="{{ route('projects.index') }}">Back</a>

    <div class="task-card">
        <h2>Create New Task</h2>

        @if ($errors->any())
            <div class="error-box">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('projects.tasks.store', $project_id) }}" method="POST" >

        @csrf

        <input type="hidden" name="project_id" value="{{ $project_id }}">

        <div class="form-group">
            <label>Task Name</label>
            <input type="text" name="title" placeholder="Enter Task Name" required>
        </div>

        <div class="form-group">
            <label>Task Description</label>
            <textarea id="editor" name ="description" ></textarea>
        </div>

        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date">
        </div>

        <div class="form-group">
            <label>Assign To</label>
            <select name="assigned_to" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="submit-btn">Create Task</button>
    </form>

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
