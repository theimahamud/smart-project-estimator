<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    protected $fillable = ['region_id', 'team_role_id', 'hourly_rate'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function teamRole()
    {
        return $this->belongsTo(TeamRole::class);
    }
}
