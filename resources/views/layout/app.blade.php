<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--- @vite(['resources/css/app.css','resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        *{
            box-sizing: border-box;
        }

        body {
            margin:0;
            padding:0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0b1220, #0f172a, #0b1220);
            color: #e2e8f0;
        }

        /* Sidebar + content layout fix */
        .layout-wrapper{
            display:flex;
            min-height:100vh;
        }

        /* Glass content area */
        .main-wrapper {
            flex:1;
            padding:35px 40px;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.04);
            border-left: 1px solid rgba(255,255,255,0.08);
            overflow-y:auto;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        /* Headings look better */
        h1,h2,h3,h4,h5{
            color:#ffffff;
            font-weight:600;
            margin-bottom:15px;
        }

        /* Default card style for all pages */
        .card-ui{
            background: rgba(255,255,255,0.05);
            border:1px solid rgba(255,255,255,0.08);
            border-radius:12px;
            padding:25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }

        /* Inputs global fix */
        input, textarea, select{
            background:#1e293b;
            border:1px solid #334155;
            color:#f1f5f9;
            border-radius:6px;
            padding:10px 12px;
        }

        input:focus, textarea:focus, select:focus{
            outline:none;
            border-color:#3b82f6;
            box-shadow:0 0 0 3px rgba(59,130,246,0.25);
        }
    </style>
</head>

<body>

<div class="layout-wrapper">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main Content --}}
    <div class="main-wrapper">
        @yield('content')
    </div>

</div>
@stack('scripts')
</body>
</html>
