<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->with('error', 'Invalid Username or Password');
    }

    private function redirectBasedOnRole($user)
    {
        $user = $user->fresh(['roles']);

        // dd($user->getRoleNames()->toArray());

        if($user->hasRole('super_admin')){
            return redirect()->route('superadmin.dashboard');
        }
        
        if($user->hasRole('admin')){
            return redirect()->route('admin.dashboard');
        }

        if($user->hasRole('manager')){
            return redirect()->route('manager.dashboard');
        }

        if($user->hasRole('employee')){
            return redirect()->route('employee.dashboard');
        }

        Auth::logout();

        return redirect()->route('login')
            ->with('error', 'No valid role assigned. Please contact administrator.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
