<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;
use App\Models\Major;
use App\Models\Teacher;
use App\Models\Generation;
use App\Models\Term;


class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'term_id',
        'teacher_id',
        'gen_id',
        'major_id',
        'duration',
    ];

    public function term(){
        return $this->belongsTo(Term::class, 'term_id');
    }
    public function major(){
        return $this->belongsTo(Major::class, 'major_id');
    }
    public function generation(){
        return $this->belongsTo(Generation::class, 'gen_id');
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
