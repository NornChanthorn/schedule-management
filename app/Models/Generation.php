<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    use HasFactory;

    protected $fillable = [
        'gen',
    ];

    // Define relationships
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
