<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DayOfWeek;
use App\Models\Term;
use App\Models\Course;
use App\Models\Room;
use App\Models\Group;

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
        'type_class'
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
        return $this->belongsTo(Course::class);
    }
    public function room(){
        return $this->belongsTo(Room::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
}
