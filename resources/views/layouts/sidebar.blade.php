
    <div class="w-64 min-h-screen bg-slate-900 text-slate-200 flex flex-col shadow-xl">

    {{-- Logo / Title --}}
    <div class="p-6 border-b border-slate-700">
    <h2 class="text-2xl font-bold text-blue tracking-wide">
    My Dashboard
    </h2>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 p-4 space-y-2 text-sm">

    <!---------------------------------------------Super Admin ----------------------------------------------------------------->
    @role('super_admin')

    <a href="{{ route('superadmin.dashboard') }}"  class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    🛠️ <span>SuperAdmin Dashboard</span></a>
    
        <a href="{{ route('users.create') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
     ➕ <span>Create User</span>
     
    <a href="{{ route('users.index') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
    <span class="text-lg">👥</span>
    <span class="font-medium">View Users</span>
    </a>

    <a href="{{ route('profile.edit') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    🙍 <span>My Profile</span>
    </a>

    @endrole

    <!---------------------------------------------Admin ---------------------------------------------------->
     @role('admin')
    <a href="{{ route('admin.dashboard') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    🛠️ <span>Admin Dashboard</span>
    </a>
    <a href="{{ route('users.create') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
     ➕ <span>Create User</span>
    </a>
    <!------------------------------------------- Create Projects --------------------------------------------------------------->

    <a href="{{ route('projects.create') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
    <span class="text-lg">➕</span>
    <span class="font-medium">Create Project</span>
    </a>
    
    <!-- View All Project -->
    <a href="{{ route('projects.index') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
    <span class="text-lg">📁</span>
    <span class="font-medium">View Projects</span>
    </a>

    <!-- View Users -->
    <a href="{{ route('users.index') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
    <span class="text-lg">👥</span>
    <span class="font-medium">View Users</span>
    </a>
    
    <a href="{{ route('profile.edit') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    🙍 <span>My Profile</span>
    </a>
    @endrole

    <!-- Managers Roles -->
    @role('manager')

    <a href="{{ route('manager.dashboard') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    📊 <span>Manager Dashboard</span>
    </a>

    <a href="{{ route('users.create') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    ➕ <span>Create User</span>
    </a>
    <a href="{{ route('users.index') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    👥 <span>View Users</span>
    </a>

        <a href="{{route ('projects.create')}}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
        ➕ <span>Create Project</span>
        </a>


        <a href="{{ route('projects.index') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
        📁 <span>View Projects</span>
        </a>

    <a href="{{ route('profile.edit') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    🙍 <span>My Profile</span>
    </a>

    @endrole

    <!------------------------------ Mangers End Roles ---------------------------------------->


    <!-------------------------------- Users Roles --------------------------------------->
    @role('employee')
    <a href="{{ route('employee.dashboard') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    🏠 <span>Employee Dashboard</span>
     </a>

     <a href="{{ route('projects.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
     📁 <span>My Projects</span>
    </a>
    
    <a href="{{ route('profile.edit') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
    🙍 <span>My Profile</span>
    </a>
    @endrole

    </nav>

    {{-- Logout --}}
    <div class="p-4 border-t border-slate-700">
    <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button
    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition font-semibold">
    Logout
    </button>
    </form>
    </div>
    </div>
