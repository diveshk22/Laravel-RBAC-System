@extends('layout.app')

@section('content')

<style>
    body{
        margin:0;
        padding:0;
        background:#0f172a;
        font-family: 'Segoe UI', sans-serif;
    }

    .bg-blur{
        position: fixed;
        inset: 0;
        z-index: -1;
        background:
            radial-gradient(circle at 15% 20%, #2563eb55, transparent 40%),
            radial-gradient(circle at 85% 30%, #7c3aed55, transparent 40%),
            radial-gradient(circle at 50% 85%, #0ea5e955, transparent 40%),
            #0f172a;
        animation: moveBg 18s ease-in-out infinite alternate;
    }

    @keyframes moveBg{
        0%{ background-position:0% 0%,100% 0%,50% 100%; }
        100%{ background-position:20% 30%,80% 10%,60% 80%; }
    }

    .glass-form{
        backdrop-filter: blur(22px);
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.15);
        box-shadow: 0 30px 80px rgba(0,0,0,0.7);
    }

    .form-label{
        color:#e5e7eb;
        font-weight:600;
        margin-bottom:8px;
        display:block;
        letter-spacing:.5px;
    }

    .form-input{
        width:100%;
        padding:14px 16px;
        border-radius:12px;
        background:#111827;
        border:1px solid #374151;
        color:#fff;
        transition:.3s;
    }

    .form-input::placeholder{
        color:#9ca3af;
    }

    .form-input:focus{
        outline:none;
        border-color:#3b82f6;
        box-shadow:0 0 0 3px #3b82f655;
        background:#0b1220;
    }

    .btn-premium{
        position:relative;
        overflow:hidden;
        background:linear-gradient(135deg,#3b82f6,#6366f1);
        padding:14px;
        border-radius:14px;
        font-weight:700;
        font-size:18px;
        color:white;
        transition:.35s;
        box-shadow:0 12px 30px rgba(59,130,246,0.35);
        border:none;
        cursor:pointer;
    }

    .btn-premium:hover{
        transform:translateY(-4px);
        box-shadow:0 20px 45px rgba(99,102,241,0.55);
    }

    .form-title{
        font-size:34px;
        font-weight:800;
        color:white;
        margin-bottom:30px;
        letter-spacing:1px;
    }
</style>

<div class="bg-blur"></div>

<div class="flex justify-center items-center min-h-screen px-4">

    <div class="glass-form w-full max-w-2xl p-12 rounded-3xl">

        <h2 class="form-title">➕ Create New Employee</h2>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-input" placeholder="Enter name" required>
            </div>

            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" placeholder="Enter email" required>
            </div>

            <div>
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Enter password" required>
            </div>

            <div>
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm password" required>
            </div>

            @if(auth()->user()->hasRole('manager'))
            <input type="hidden" name="role" value="employee">
            @endif

            @if(auth()->user()->hasRole(['admin' , 'super_admin']))
            <div>
            <label class="form-label">Role</label>
            <select name="role" class="form-input" required>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="employee">Employee</option>
            </select>
            </div>
            @endif


            <button type="submit" class="btn-premium w-full mt-4">
                🚀 Create Employee
            </button>

        </form>

    </div>

</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        title: 'Success 🎉',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK',
        background: '#0f172a',
        color: '#fff',
        confirmButtonColor: '#3b82f6'
    });
</script>

@endif

@endsection
