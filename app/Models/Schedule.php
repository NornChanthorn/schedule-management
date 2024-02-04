<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DayOfWeek;
use App\Models\Term;
use App\Models\Course;
use App\Models\Room;
use App\Models\Group;
use App\Models\Generation;
use App\Models\Major;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_start',
        'time_end',
        'day_id',
        'term_id',
        'course_id',
        'room_id',
        'group_id',
        'major_id',
        'gen_id'

    ];

    // Define relationships
    public function day()
    {
        return $this->belongsTo(DayOfWeek::class, 'day_id');
    }
    public function term(){
        return $this->belongsTo(Term::class, 'term_id');
    }
    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function room(){
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function generation(){
        return $this->belongsTo(Generation::class, 'gen_id');
    }
    public function major(){
        return $this->belongsTo(Major::class, 'major_id');
    }
}
