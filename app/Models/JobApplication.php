<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id', 'cv_path', 'avatar_path', 'dob', 'gender', 
        'preferred_loc', 'salary', 'note', 'status'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}   