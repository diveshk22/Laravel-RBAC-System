<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];
    
    // Project users relationship - Admin/SuperAdmin only
    Public function users()
    {
        return $this->belongsToMany(User::class , 'project_users');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
