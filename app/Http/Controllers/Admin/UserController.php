<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /* ===========================
       USER LIST (ROLE BASED)
    ============================*/
public function index(Request $request)
{
    $authUser = auth()->user();
    $search = $request->search;

    $usersQuery = User::with('creator');

    // ================= SUPER ADMIN =================
    if ($authUser->hasRole('super_admin')) {

        $usersQuery->where('id', '!=', $authUser->id);

    }
    // ================= ADMIN =================
    elseif ($authUser->hasRole('admin')) {

        $usersQuery->whereHas('roles', function ($q) {
            $q->whereIn('name', ['manager', 'employee', 'admin']);
        });

    }
    // ================= MANAGER =================
    elseif ($authUser->hasRole('manager')) {

        $usersQuery->whereHas('roles', function ($q) {
            $q->where('name', 'employee');
        })->where('created_by', auth()->id());
    }

    // ================= SEARCH (COMMON FOR ALL) =================
    if (!empty($search)) {
        $usersQuery->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    $users = $usersQuery->latest()->paginate(10)->withQueryString();

    return view('usercreation.userIndex', compact('users'));
}

    /* ===========================
       SHOW CREATE FORM
    ============================*/
public function create()
{
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            // Super Admin can create all roles
            $roles = ['admin', 'manager', 'employee'];
        } elseif ($user->hasRole('admin')) {
            // Admin can create manager + employee
            $roles = ['admin', 'manager', 'employee'];
        } else {
            // Others can only create employee
            $roles = ['employee'];
        }

        return view('usercreation.CreateUser', compact('roles'));
}

    /* ===========================
       STORE USER
    ============================*/
public function store(Request $request)
{

    
    $authUser = auth()->user();

    //  dd($authUser->getRoleNames()); 
    $request->validate([
        'name' => 'required',
        'email'=> 'required|email|',
        'password' => 'required|min:6|confirmed',
        'role' => 'required',
    ]);

    // ✅ Allowed Roles Based on Logged-in User
    $allowedRoles = [];

    if ($authUser->hasRole('super_admin')) {
        $allowedRoles = ['admin', 'manager', 'employee'];
    }
    elseif ($authUser->hasRole('admin')) {
        $allowedRoles = [ 'admin','manager', 'employee'];
    }
    elseif ($authUser->hasRole('manager')) {
        $allowedRoles = ['employee'];
    }

    // ❗ Single Check Only
    if (!in_array($request->role, $allowedRoles)) {
        abort(403, 'Unauthorized Role');
    }

    // Create User
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'created_by' => $authUser->id,
    ]);

    // Assign Role
    $user->assignRole($request->role);

    return redirect()->back()->with('success', 'User created successfully');
}

    /* ===========================
       EDIT USER
    ============================*/
public function edit($id)
{
        $authUser = auth()->user();
        $user = User::findOrFail($id);

        // Manager can edit only his created users
        if ($authUser->hasRole('manager') && $user->created_by != $authUser->id) {
            abort(403);
        }
        if ($authUser->hasRole('admin')) {
            $roles = \Spatie\Permission\Models\Role::whereIn('name', ['admin', 'manager', 'employee'])->get();
        } else {
            $roles = \Spatie\Permission\Models\Role::where('name', 'employee')->get();
        }

        return view('usercreation.useredit', compact('user', 'roles'));
}


    /* ===========================
       UPDATE USER
    ============================*/
public function update(Request $request, $id)
{
        $authUser = auth()->user();
        $user = User::findOrFail($id);

        if ($authUser->hasRole('manager') && $user->created_by != $authUser->id) {
            abort(403);
        }

        //validation users 
        $request->validate([
            'name' => 'required',
            'email'=> 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'nullable|in:admin,manager,employee',
        ]);

         // Role Restrictions
         if ($authUser->hasRole('manager') && $request->role !== 'employee') {
            abort(403);
        }

        $user->name = $request->name;
        $user->email = $request->email;
       

        // Update role if changed
        // Role Update (Super Admin + Admin)
        if ($request->filled('role')) {

            if ($authUser->hasRole('super_admin') && in_array($request->role, ['admin', 'manager', 'employee'])) {
                $user->syncRoles([$request->role]);
            }

            elseif ($authUser->hasRole('admin') && in_array($request->role, ['admin', 'manager', 'employee'])) {
                $user->syncRoles([$request->role]);
            }
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('updated', 'Employee updated successfully!');
}


    /* ===========================
       DELETE USER
    ============================*/
public function destroy(User $user)
{
        $authUser = auth()->user();

        // Manager cannot delete
        if ($authUser->hasAnyRole('manager')) {
            abort(403);
        }

        $user->delete();

        return back()->with('success', 'Employee deleted successfully');
    }

}
