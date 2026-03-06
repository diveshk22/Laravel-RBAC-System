<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Employee\ProfileController;
use App\Http\Controllers\Managers\ManagerDashboardController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'))->name('/');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/super-admin/dashboard', [SuperAdminDashboardController::class, 'index'])
        ->name('superadmin.dashboard');
});

/*
|--------------------------------------------------------------------------
| ADMIN + MANAGER COMMON ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin|super_admin|manager|employee'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('/projects/upload', [ProjectController::class, 'upload'])
        ->name('projects.upload');
    /*
    |--------------------------------------------------------------------------
    | USERS MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class)->except(['show']);

    Route::post('/users/{user}/role', [UserController::class, 'changeRole'])
        ->name('users.changeRole');

    Route::post('/users/{user}/make-manager', [UserController::class, 'makeManager'])
        ->name('users.makeManager');

    /*
    |--------------------------------------------------------------------------
    | PROJECTS
    |--------------------------------------------------------------------------
    */

    Route::resource('projects', ProjectController::class);

    /*
    |--------------------------------------------------------------------------
    | TASKS (NESTED)
    |--------------------------------------------------------------------------
    */

    Route::prefix('projects/{project}/tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])
            ->name('projects.tasks.index');

        Route::get('/create', [TaskController::class, 'create'])
            ->name('projects.tasks.create');

        Route::post('/', [TaskController::class, 'store'])
            ->name('projects.tasks.store');

        Route::get('/{task}/edit', [TaskController::class, 'edit'])
            ->name('projects.tasks.edit');

        Route::delete('/{task}', [TaskController::class, 'destroy'])
            ->name('projects.tasks.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | SINGLE TASK OPERATIONS
    |--------------------------------------------------------------------------
    */

    Route::prefix('tasks')->group(function () {

        Route::get('{task}', [TaskController::class, 'show'])
            ->name('projects.task.show');

        Route::get('{task}/edit', [TaskController::class, 'edit'])
            ->name('projects.task.edit');

        Route::put('{task}', [TaskController::class, 'update'])
            ->name('projects.task.update');

        Route::post('{task}/assign', [TaskController::class, 'assign'])
            ->name('projects.task.assign');

        Route::delete('{task}', [TaskController::class, 'destroy'])
            ->name('projects.task.delete');

        Route::post('{task}/status', [TaskController::class, 'updateStatus'])
            ->name('projects.task.updateStatus');
    });
});

/*
|--------------------------------------------------------------------------
| MANAGER DASHBOARD ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| EMPLOYEE ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/projects', [ProjectController::class, 'index'])
            ->name('projects.index');

        Route::get('/projects/{project}', [ProjectController::class, 'show'])
            ->name('projects.show');
        
        Route::post('/projects/upload', [App\Http\Controllers\Admin\ProjectController::class, 'upload'])
            ->name('projects.upload');

        Route::get('/projects/{project}/tasks', [TaskController::class, 'index'])
            ->name('projects.tasks.index');

        Route::get('/tasks/{task}', [TaskController::class, 'show'])
            ->name('projects.task.show');

        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
            ->name('projects.task.edit');

        Route::put('/tasks/{task}', [TaskController::class, 'update'])
            ->name('projects.task.update');

        Route::get('/my-tasks', [TaskController::class, 'myTasks'])
            ->name('mytasks');

        Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
            ->name('task.updateStatus');
    });

/*
|--------------------------------------------------------------------------
| PROFILE (ALL AUTH USERS)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');
}); 