<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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

Route::get('/', fn () => view('welcome'))->name('home');

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

    Route::get('/super-admin/dashboard',
        [SuperAdminDashboardController::class, 'index'])
        ->name('superadmin.dashboard');

});

/*
|--------------------------------------------------------------------------
| ADMIN / MANAGER / EMPLOYEE COMMON
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:admin|super_admin|manager|employee'])->group(function () {

    Route::get('/admin/dashboard',
        [AdminDashboardController::class,'index'])
        ->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class)->except(['show']);

    Route::post('/users/{user}/role',
        [UserController::class,'changeRole'])
        ->name('users.changeRole');

    Route::post('/users/{user}/make-manager',
        [UserController::class,'makeManager'])
        ->name('users.makeManager');

    /*
    |--------------------------------------------------------------------------
    | PROJECTS
    |--------------------------------------------------------------------------
    */

    Route::resource('projects', ProjectController::class);

    Route::post('/projects/upload',
        [ProjectController::class,'upload'])
        ->name('projects.upload');

    /*
    |--------------------------------------------------------------------------
    | TASKS
    |--------------------------------------------------------------------------
    */

    Route::prefix('projects/{project}/tasks')->group(function () {

        Route::get('/',
            [TaskController::class,'index'])
            ->name('projects.tasks.index');

        Route::get('/create',
            [TaskController::class,'create'])
            ->name('projects.tasks.create');

        Route::post('/',
            [TaskController::class,'store'])
            ->name('projects.tasks.store');

        Route::get('/{task}/edit',
            [TaskController::class,'edit'])
            ->name('projects.tasks.edit');

        Route::delete('/{task}',
            [TaskController::class,'destroy'])
            ->name('projects.tasks.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | SINGLE TASK
    |--------------------------------------------------------------------------
    */

    Route::prefix('tasks')->group(function () {

        Route::get('{task}',
            [TaskController::class,'show'])
            ->name('tasks.show');

        Route::put('{task}',
            [TaskController::class,'update'])
            ->name('tasks.update');

        Route::post('{task}/status',
            [TaskController::class,'updateStatus'])
            ->name('tasks.updateStatus');

        Route::post('{task}/comment',
            [TaskController::class,'addComment'])
            ->name('tasks.comment');

    });

});

/*
|--------------------------------------------------------------------------
| MANAGER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {

        Route::get('/dashboard',
            [ManagerDashboardController::class,'index'])
            ->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| EMPLOYEE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        Route::get('/dashboard',
            [EmployeeDashboardController::class,'index'])
            ->name('dashboard');

        Route::get('/my-tasks',
            [TaskController::class,'myTasks'])
            ->name('mytasks');

});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/profile',
        [ProfileController::class,'edit'])
        ->name('profile.edit');

    Route::put('/profile/update',
        [ProfileController::class,'update'])
        ->name('profile.update');

});

/*
|--------------------------------------------------------------------------
| MAIL TEST
|--------------------------------------------------------------------------
*/

Route::get('/test-mail', function () {

    Mail::raw('This is a test mail', function ($message) {

        $message->to('test@example.com')
                ->subject('Mailtrap Test');

    });

    return "Mail Sent Successfully";

});