<?php

namespace App\Models;

use App\Enums\QualityLevel;
use App\Enums\TeamSeniority;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'default_country',
        'default_tech_stack',
        'default_quality_level',
        'default_team_seniority',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'default_tech_stack' => 'array',
            'default_quality_level' => QualityLevel::class,
            'default_team_seniority' => TeamSeniority::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
