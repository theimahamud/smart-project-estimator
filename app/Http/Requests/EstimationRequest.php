<?php

namespace App\Http\Requests;

use App\Enums\DomainType;
use App\Enums\ProjectType;
use App\Enums\QualityLevel;
use App\Enums\RequirementsQuality;
use App\Enums\TeamSeniority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EstimationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow authenticated users to make estimations
    }

    public function rules(): array
    {
        return [
            // Project Basics
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'project_type' => ['required', 'string', Rule::enum(ProjectType::class)],
            'domain_type' => ['required', 'string', Rule::enum(DomainType::class)],

            // Requirements
            'functional_requirements' => ['required', 'string', 'max:5000'],
            'technical_requirements' => ['nullable', 'string', 'max:5000'],
            'quality_requirements' => ['nullable', 'string', 'max:2000'],
            'constraints' => ['nullable', 'string', 'max:2000'],
            'assumptions' => ['nullable', 'string', 'max:2000'],
            'requirements_quality' => ['required', 'string', Rule::enum(RequirementsQuality::class)],

            // Estimation Context
            'desired_quality_level' => ['required', 'string', Rule::enum(QualityLevel::class)],
            'team_seniority' => ['required', 'string', Rule::enum(TeamSeniority::class)],
            'include_testing' => ['boolean'],
            'include_documentation' => ['boolean'],
            'include_deployment' => ['boolean'],
            'include_maintenance' => ['boolean'],
            'buffer_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'additional_context' => ['nullable', 'string', 'max:1000'],

            // Client information (optional for saving)
            'client_id' => ['nullable', 'exists:clients,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'name.max' => 'Project name cannot exceed 255 characters.',
            'description.required' => 'Project description is required.',
            'description.max' => 'Project description cannot exceed 2000 characters.',
            'project_type.required' => 'Please select a project type.',
            'domain_type.required' => 'Please select a domain type.',
            'functional_requirements.required' => 'Functional requirements are required.',
            'functional_requirements.max' => 'Functional requirements cannot exceed 5000 characters.',
            'technical_requirements.max' => 'Technical requirements cannot exceed 5000 characters.',
            'quality_requirements.max' => 'Quality requirements cannot exceed 2000 characters.',
            'constraints.max' => 'Constraints cannot exceed 2000 characters.',
            'assumptions.max' => 'Assumptions cannot exceed 2000 characters.',
            'requirements_quality.required' => 'Please indicate the quality of your requirements.',
            'desired_quality_level.required' => 'Please select your desired quality level.',
            'team_seniority.required' => 'Please select team seniority level.',
            'buffer_percentage.numeric' => 'Buffer percentage must be a number.',
            'buffer_percentage.min' => 'Buffer percentage cannot be negative.',
            'buffer_percentage.max' => 'Buffer percentage cannot exceed 100%.',
            'additional_context.max' => 'Additional context cannot exceed 1000 characters.',
            'client_id.exists' => 'Selected client does not exist.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'project name',
            'description' => 'project description',
            'project_type' => 'project type',
            'domain_type' => 'domain type',
            'functional_requirements' => 'functional requirements',
            'technical_requirements' => 'technical requirements',
            'quality_requirements' => 'quality requirements',
            'requirements_quality' => 'requirements quality',
            'desired_quality_level' => 'quality level',
            'team_seniority' => 'team seniority',
            'buffer_percentage' => 'buffer percentage',
            'additional_context' => 'additional context',
            'client_id' => 'client',
        ];
    }
}
