<?php

namespace App\Models;

use App\Enums\ConfidenceLevel;
use App\Enums\EstimateStatus;
use App\Enums\QualityLevel;
use App\Enums\RequirementsQuality;
use App\Enums\TeamSeniority;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estimate extends Model
{
    protected $fillable = [
        'project_id',
        'version',
        'status',
        'requirements',
        'requirements_quality',
        'quality_level',
        'team_seniority',
        'confidence_level',
        'total_hours',
        'min_hours',
        'max_hours',
        'total_cost',
        'min_cost',
        'max_cost',
        'breakdown',
        'assumptions',
        'risks',
        'recommendations',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'status' => EstimateStatus::class,
            'requirements_quality' => RequirementsQuality::class,
            'quality_level' => QualityLevel::class,
            'team_seniority' => TeamSeniority::class,
            'confidence_level' => ConfidenceLevel::class,
            'total_hours' => 'decimal:2',
            'min_hours' => 'decimal:2',
            'max_hours' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'min_cost' => 'decimal:2',
            'max_cost' => 'decimal:2',
            'breakdown' => 'array',
            'assumptions' => 'array',
            'risks' => 'array',
            'recommendations' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
