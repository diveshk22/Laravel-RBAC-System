@extends('layout.app')

@section('content')

<style>
    body{
        background:#0f172a;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .page-title{
        font-size: 36px;
        font-weight: 800;
        color: white;
        margin-bottom: 30px;
        letter-spacing: 1px;
    }

    .form-wrapper{
        max-width: 600px;
        margin: auto;
        padding: 40px;
        border-radius: 20px;
        backdrop-filter: blur(20px);
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.15);
        box-shadow: 0 25px 60px rgba(0,0,0,0.6);
    }

    .form-group{
        margin-bottom: 25px;
        display: flex;
        flex-direction: column;
    }

    label{
        color: #cbd5e1;
        font-weight: 600;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    input{
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255,255,255,0.08);
        color: white;
        font-size: 14px;
        outline: none;
        transition: 0.3s ease;
    }

    input:focus{
        border-color: #10b981;
        box-shadow: 0 0 10px #10b981;
    }

    .btn{
        padding: 14px 20px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-weight: 700;
        letter-spacing: 1px;
        transition: 0.3s ease;
        color: white;
    }

    .btn-update{
        background: #10b981;
        width: 100%;
        margin-top: 10px;
    }

    .btn-update:hover{
        box-shadow: 0 0 20px #10b981;
        transform: translateY(-2px);
    }

</style>

    <a href="{{ route('users.index') }}" class="btn btn-user">Back to Users List</a>
<div class="p-8">

    <h2 class="page-title">✏️ Edit User</h2>

    <div class="form-wrapper">

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" required>
            </div>

            @if(auth()->user()->hasAnyRole(['admin','super_admin']))
            <div class="form-group">
                <label>Role</label>

                <select name="role" class="form-control">

                    <option value="admin"
                        {{ $user->hasRole('admin') ? 'selected' : '' }}>
                        Admin
                    </option>

                    <option value="manager"
                        {{ $user->hasRole('manager') ? 'selected' : '' }}>
                        Manager
                    </option>

                    <option value="employee"
                        {{ $user->hasRole('employee') ? 'selected' : '' }}>
                        Employee
                    </option>

                </select>
            </div>
            @endif
            <div class="form-group">
                <label>Password (Leave blank to keep current)</label>
                <input type="password" name="password" placeholder="New Password">
                <input type="password" name="password_confirmation" placeholder="Confirm Password">
            </div>

            <button type="submit" class="btn btn-update">
                Update Employee
            </button>

        </form>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('updated'))
<script>
Swal.fire({
    title: 'Updated ✅',
    text: "{{ session('updated') }}",
    icon: 'success',
    confirmButtonColor: '#10b981',
    background: '#0f172a',
    color: '#fff'
});
</script>
@endif


@endsection
