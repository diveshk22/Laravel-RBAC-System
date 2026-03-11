<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Dashboard for user management

    public function dashboard()
    {
         $totalUsers = User::count();

         $todayUsers  = User::role('user')
            ->whereDate('created_at', Carbon::today())
            ->count();
        return view('admin.dashboard', compact('totalUsers','todayUsers'));
    }

    // Dashboard for task management
    public function showDashboard()
    {
        $totalTasks = Task::count();
        $todayTasks = Task::whereDate('created_at', Carbon::today())->count();    
        $pendingTasks = Task::where('status', 'pending')->count();
        // $completedTasks = Task::where('status', 'completed')->count();
        
        return view('admin.taskdashboard', compact('totalTasks', 'todayTasks', 'pendingTasks'));
    }

    public function index()
    {
        $totalUsers = User::count();
        $todayUsers = User::whereDate('created_at', Carbon::today())->count();
        $totalTasks = Task::count();
        $todayTasks = Task::whereDate('created_at', Carbon::today())->count();
        $pendingTasks = Task::where('status', 'pending')->count();
        return view('admin.AdminDashboard', compact('totalUsers', 'todayUsers', 'totalTasks', 'todayTasks', 'pendingTasks'));
    }
}
