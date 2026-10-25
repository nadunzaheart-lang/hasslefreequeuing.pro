<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
    'queue_number',
    'student_name',
    'student_id',
    'transaction',
    'status',
    'window_number', // <-- Add this line
];

}