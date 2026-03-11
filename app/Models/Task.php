<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    // Mass assignable attributes
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'assigned_to',
        'status',
        'user_id',
        'project_id',
    ];

    // Task statuses
    public const STATUSES  = [
        'Pending','In progress','Completed'
    ];

    // Task has many comments
    public function comments()
    { 
        return $this->hasMany(Comment::class);
    }

    // Task belongs to a user (creator) - Admin/SuperAdmin only
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Task assigned to a user - Admin/SuperAdmin only
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function project()
    {
        return $this->belongsTo(project::class);
    }
}
