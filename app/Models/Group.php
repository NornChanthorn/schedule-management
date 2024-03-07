<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Major;
use App\Models\Generation;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'gen_id',
        'major_id'
    ];

    // Define relationships
    public function generation(){
        return $this->belongsTo(Generation::class, 'gen_id');
    }
    public function major(){
        return $this->belongsTo(Major::class, 'major_id');
    }

}
