@extends('layout.app')

@section('content')

<style>
    body{
        margin:0;
        font-family: 'Inter', sans-serif;
        background: #0f172a;
        color:#fff;
        overflow-x:hidden;
    }

    /* Animated soft gradient layer */
    body::before{
        content:"";
        position:fixed;
        top:-50%;
        left:-50%;
        width:200%;
        height:200%;
        background: radial-gradient(circle at center, #0ea5e9 0%, transparent 40%),
                    radial-gradient(circle at 70% 30%, #1e3a8a 0%, transparent 40%),
                    radial-gradient(circle at 30% 70%, #1e293b 0%, transparent 40%);
        animation: moveBg 25s linear infinite;
        z-index:-1;
        opacity:.35;
    }

    @keyframes moveBg{
        0%{transform:rotate(0deg);}
        100%{transform:rotate(360deg);}
    }

    .glass{
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: 0 20px 60px rgba(0,0,0,0.6);
    }

    .header-card{
        transition:.4s;
    }
    .header-card:hover{
        transform: translateY(-6px);
    }

    .avatar{
        width:72px;
        height:72px;
        border-radius:50%;
        border:2px solid rgba(255,255,255,0.3);
        transition:.4s;
        object-fit:cover;
    }
    .avatar:hover{
        transform:scale(1.1);
        border-color:#fff;
    }

    .title-big{
        font-size:50px;
        font-weight:800;
        letter-spacing:1px;
    }

    .glow{
        position: fixed;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        pointer-events: none;
        background: radial-gradient(circle, rgba(14,165,233,0.25), transparent 60%);
        transform: translate(-50%, -50%);
        z-index: 0;
    }
</style>

<div class="glow" id="glow"></div>

<div class="min-h-screen py-14 px-6 relative z-10">

    {{-- Header --}}
    <div class="max-w-7xl mx-auto mb-14">
        <div class="glass header-card rounded-3xl p-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h2 class="text-3xl font-bold">
                    Welcome, {{ auth()->user()->name }} 👋
                </h2>

                <p class="mt-4 text-lg">
                    Role:   
                    <span class="px-4 py-1 rounded-full text-sm bg-gradient-to-r from-blue-600 to-indigo-600">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </span>
                </p>
            </div>

            <a href="{{ route('profile.edit') }}">
                <img
                src="{{ auth()->user()->photo 
                ? asset('storage/' . auth()->user()->photo) 
                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4f46e5&color=fff&size=70' }}"
                class="avatar">
            </a>
        </div>
    </div>

    {{-- Intro --}}
    <div class="max-w-5xl mx-auto mb-16">
        <div class="glass rounded-3xl p-14 text-center">
            <h1 class="title-big mb-4">Dashboard</h1>
            <p class="text-gray-300 text-lg">
                Task and project functionality has been removed for users.
            </p>
        </div>
    </div>

</div>

<script>
    // Premium mouse glow effect
    const glow = document.getElementById('glow');
    document.addEventListener('mousemove', (e) => {
        glow.style.left = e.clientX + 'px';
        glow.style.top = e.clientY + 'px';
    });
</script>

@endsection
