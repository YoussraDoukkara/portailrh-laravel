<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $casts = [
        'is_department_head' => 'boolean',
        'is_team_head' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveAbsenceRequests()
    {
        return $this->hasMany(LeaveAbsenceRequest::class);
    }
}
