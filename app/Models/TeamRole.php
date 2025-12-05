<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamRole extends Model
{
    protected $fillable = ['name', 'description', 'base_hourly_rate', 'is_active'];

    public function pricingTiers()
    {
        return $this->hasMany(PricingTier::class);
    }

    public function breakdowns()
    {
        return $this->hasMany(EstimateBreakdown::class);
    }
}
