<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name', 'code', 'cost_multiplier', 'is_active'];

    public function estimates()
    {
        return $this->hasMany(Estimate::class);
    }

    public function pricingTiers()
    {
        return $this->hasMany(PricingTier::class);
    }
}
