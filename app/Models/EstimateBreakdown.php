<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimateBreakdown extends Model
{
    protected $fillable = ['estimate_id', 'team_role_id', 'hours', 'hourly_rate', 'cost'];

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }

    public function teamRole()
    {
        return $this->belongsTo(TeamRole::class);
    }
}
