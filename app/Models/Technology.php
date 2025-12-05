<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    protected $fillable = ['name', 'category', 'description', 'complexity_multiplier', 'is_active'];
}
