<?php

namespace App\Http\Controllers\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('Employee.ProfileEdit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif|max:2048',
        ]);

        $user = auth()->user();

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
            $user->photo = $imagePath;
        }

        $user->save();
        
        return back()->with('success', 'Profile updated successfully.');
    }
}