<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Window extends Model
{
    protected $fillable = ['window_number', 'name', 'letter_prefix', 'is_active'];
}