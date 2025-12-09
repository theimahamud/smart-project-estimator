<?php

namespace App\Models;

use App\Enums\DomainType;
use App\Enums\ProjectType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'client_id',
        'name',
        'description',
        'project_type',
        'domain_type',
        'country',
        'tech_stack',
    ];

    protected function casts(): array
    {
        return [
            'project_type' => ProjectType::class,
            'domain_type' => DomainType::class,
            'tech_stack' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function estimates(): HasMany
    {
        return $this->hasMany(Estimate::class);
    }

    public function latestEstimate(): HasMany
    {
        return $this->hasMany(Estimate::class)->latest();
    }
}
