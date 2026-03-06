<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;

class ProjectController extends Controller
{
    // ek me kar dia isko har jgah user where has par use kar sako
    private function getProjectUsers()
    {
        return User::whereHas('roles', function($query) {
        $query->whereIn('name', ['manager', 'employee']);
        })->get(); 
    }

    public function index()
    {
        $user = auth()->user();
        $search = request()->search;

        // Base Query
        if ($user->hasRole('admin')) {
            $query = Project::with('users');
        } else {
            $query = $user->projects()->with('users');
        }

        // Search filter
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    
    public function create()
    {
        $users = $this->getProjectUsers();

        return view('admin.projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'users' => 'required|array'
        ]);

        // ✅ Project create
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // ✅ Attach users (many-to-many)
        $userIds = $request->users;
        
        // ✅ Auto-assign Manager who created the project
        if (auth()->user()->hasRole('manager') && !in_array(auth()->id(), $userIds)) {
            $userIds[] = auth()->id();
        }
        
        $project->users()->attach($userIds);

        // managers to provide access project create
        $route = auth()->user()->hasRole('manager') ? 'manager.projects.index' : 'admin.projects.index';
        return back()
            ->with('success', 'Project Created Successfully');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id); 
        $project->users()->detach();
        $project->delete(); 
        return back() ->with('success', 'Project Deleted Successfully');
    }

    // Edit Project
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $users = $this->getProjectUsers();
        return view('admin.projects.editproject', compact('project', 'users'));
    }

    // Update Project
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->has('users')) {
            $project->users()->sync($request->users);
        }

        return back()
            ->with('success', 'Project Updated Successfully');
    }
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            
            // Sanitize the filename to remove spaces (like in "manish kumar.jpg")
            $safeName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            // Move the file
            $file->move(public_path('uploads'), $safeName);

            $url = asset('uploads/' . $safeName);

            // SUCCESS: CKEditor requires 'uploaded' => true or 1
            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }

        // ERROR: Return a message CKEditor can display
        return response()->json([
            'uploaded' => false,
            'error' => [
                'message' => 'could not upload this image.'
            ]
        ], 400);
    }
}
