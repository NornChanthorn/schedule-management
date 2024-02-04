<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'term_id',
        'teacher_id',
        'duration',
    ];

    public function term(){
        return $this->belongsTo(Term::class, 'term_id');
    }
    public function teacher(){
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'course_id');
    }

    // Define relationships

}
