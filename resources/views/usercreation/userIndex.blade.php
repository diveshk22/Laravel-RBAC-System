 @extends('layout.app')

@section('content')

<style>
    body {
        background: #0f172a;
        font-family: system-ui, -apple-system, sans-serif;
    }

    /* Container */
    .container-box {
        padding: 40px;
    }

    /* Page Title */
    .page-title {
        font-size: 34px;
        font-weight: 800;
        color: white;
        margin-bottom: 25px;
    }

    /* ================= SEARCH ================= */

    .search-form {
        display: flex;
        gap: 12px;
        margin-bottom: 25px;
        align-items: center;
    }

    .search-input {
        padding: 12px 18px;
        width: 280px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: white;
        outline: none;
        transition: 0.3s;
        backdrop-filter: blur(10px);
    }

    .search-input::placeholder {
        color: #9ca3af;
    }

    .search-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
    }

    .search-btn {
        padding: 12px 22px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
    }

    /* ================= TABLE ================= */

    .table-wrapper {
        backdrop-filter: blur(25px);
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        color: #e5e7eb;
    }

    thead {
        background: rgba(255, 255, 255, 0.07);
        font-size: 13px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    thead th {
        padding: 16px 20px;
        text-align: left;
    }

    tbody tr {
        transition: 0.3s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    tbody tr:hover {
        background: rgba(255, 255, 255, 0.06);
    }

    td {
        padding: 16px 20px;
    }

    .email {
        color: #60a5fa;
        font-weight: 600;
    }

    /* Buttons */

    .actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        align-items: center;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        color: white;
        text-decoration: none;
    }

    .btn-admin { background: #dc2626; }
    .btn-manager { background: #10b981; }
    .btn-user { background: #2563eb; }
    .delete-btn { background: #ef4444; }

    .btn:hover {
        opacity: 0.85;
        transform: translateY(-2px);
    }

    .text-center {
        text-align: center;
    }
</style>

<div class="container-box">

    <h2 class="page-title">👥 Users List</h2>

    <!-- SEARCH -->
    <form method="GET"
          action="{{ route('users.index') }}"
          class="search-form">

        <input type="search"
               name="search"
               value="{{ request('search') }}"
               placeholder="🔍 Search users..."
               class="search-input">

        <button type="submit"
                class="search-btn">
            Search
        </button>

    </form>

    <!-- TABLE -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created By</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $key }}</td>
                        <td>{{ $user->name }}</td>
                        <td class="email">{{ $user->email }}</td>

                        <td>
                            @php
                                $role = $user->getRoleNames()->first();
                            @endphp

                            @if ($role === 'admin')
                                <span class="btn btn-admin">Admin</span>
                            @elseif ($role === 'manager')
                                <span class="btn btn-manager">Manager</span>
                            @else
                                <span class="btn btn-user">Employee</span>
                            @endif
                        </td>

                        <td>
                            @if ($user->creator)
                                {{ $user->creator->name }}
                                ({{ $user->creator->getRoleNames()->first() }})
                            @else
                                Self
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="actions">

                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="btn btn-user">
                                    Edit
                                </a>

                                @if (auth()->user()->hasAnyRole(['admin', 'super_admin']))
                                    <form id="delete-form-{{ $user->id }}"
                                          action="{{ route('users.destroy', $user->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                onclick="confirmDelete({{ $user->id }})"
                                                class="btn delete-btn">
                                            Delete
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This user will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            background: '#0f172a',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

@if (session('deleted'))
<script>
    Swal.fire({
        title: 'Deleted 🗑️',
        text: "{{ session('deleted') }}",
        icon: 'success',
        confirmButtonColor: '#ef4444',
        background: '#0f172a',
        color: '#fff'
    });
</script>
@endif

@endsection