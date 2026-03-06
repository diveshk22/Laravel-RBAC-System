@extends('layout.app')

@section('content')

<div class="p-8 text-slate-200">

    {{-- Welcome Section --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg mb-8">
        <h1 class="text-3xl font-bold mb-2">
            Welcome, {{ auth()->user()->name }}
        </h1>
        <p class="text-slate-400">
            Here is your team overview
        </p>
    </div>

    {{-- Stats Section --}}
    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-8">

        {{-- Total Users Card --}}
        <div class="stat-card">
            <h3>Total Users</h3>
            <p class="stat-number text-indigo-400">
                {{ $totalUsers }}
            </p>
        </div>

    </div>

    {{-- Manager Info Message --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-lg">
        <div class="p-6 text-center">
            <h2 class="text-xl font-semibold mb-4">
                Manager Dashboard
            </h2>
            <p class="text-slate-400">
                Task and project functionality has been removed for managers.
            </p>
        </div>
    </div>

</div>

{{-- Custom Styles --}}
<style>
    .stat-card {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    .stat-card h3 {
        color: #94a3b8;
        font-size: 14px;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        margin-top: 8px;
    }
</style>

@endsection