<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $fillable = ['estimate_id', 'feature_name', 'description', 'complexity_level', 'estimated_hours'];

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }
}
