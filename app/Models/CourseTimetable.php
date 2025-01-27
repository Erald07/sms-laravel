<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CourseTimetable extends Model
{
    use HasFactory;


    protected $fillable = [
        'course_id', 'date', 'start', 'end', 'room', 'teacher_id'
    ];

    protected static function booted()
    {
        static::creating(function ($timetable) {
            $timetable->teacher_id = Auth::id();
        });

        static::updating(function ($timetable) {
            $timetable->teacher_id = Auth::id();
        });
    }

    public function subject()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function timetablePresences()
    {
        return $this->hasMany(TimetablePresence::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
