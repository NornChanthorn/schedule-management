<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'f_name',
        'l_name',
        'gender',
        'dob',
        'phone_num',
    ];

     // Define relationships
     public function user()
     {
         return $this->belongsTo(User::class);
     }
}
