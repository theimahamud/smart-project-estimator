<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estimate extends Model
{
    protected $fillable = [
        'user_id',
        'project_name',
        'raw_requirements',
        'parsed_features',
        'region_id',
        'selected_technologies',
        'team_size',
        'total_cost',
        'estimated_hours',
        'estimated_days',
        'complexity_level',
        'team_composition',
        'ai_response',
        'status',
    ];

    protected $casts = [
        'parsed_features' => 'array',
        'selected_technologies' => 'array',
        'team_composition' => 'array',
        'ai_response' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(Requirement::class);
    }

    public function breakdowns(): HasMany
    {
        return $this->hasMany(EstimateBreakdown::class);
    }
}
