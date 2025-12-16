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
        $rules = [
            // Project Basics
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'project_type' => ['required', 'string', Rule::enum(ProjectType::class)],
            'domain_type' => ['required', 'string', Rule::enum(DomainType::class)],

            // Input method
            'input_method' => ['required', 'string', 'in:text,pdf'],

            // Requirements quality (always required)
            'requirements_quality' => ['required', 'string', Rule::enum(RequirementsQuality::class)],
        ];

        // Conditional validation based on input method
        if ($this->input('input_method') === 'pdf') {
            $rules['requirements_file'] = ['required', 'file', 'mimes:pdf', 'max:10240']; // 10MB max
        } else {
            // Text input requirements
            $rules['functional_requirements'] = ['required', 'string', 'max:5000'];
            $rules['technical_requirements'] = ['nullable', 'string', 'max:5000'];
            $rules['quality_requirements'] = ['nullable', 'string', 'max:2000'];
            $rules['constraints'] = ['nullable', 'string', 'max:2000'];
            $rules['assumptions'] = ['nullable', 'string', 'max:2000'];
        }

        return array_merge($rules, [
            // Estimation Context
            'desired_quality_level' => ['required', 'string', Rule::enum(QualityLevel::class)],
            'team_seniority' => ['required', 'string', Rule::enum(TeamSeniority::class)],
            'include_testing' => ['boolean'],
            'include_documentation' => ['boolean'],
            'include_deployment' => ['boolean'],
            'include_maintenance' => ['boolean'],
            'buffer_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'additional_context' => ['nullable', 'string', 'max:1000'],

            // Team Configuration
            'available_team_size' => ['required', 'integer', 'min:1', 'max:50'],
            'work_hours_per_day' => ['required', 'integer', 'in:4,6,8,10'],
            'target_budget' => ['nullable', 'numeric', 'min:1000', 'max:10000000'],

            // Custom hourly rates (all optional)
            'custom_rates' => ['nullable', 'array'],
            'custom_rates.backend_developer' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'custom_rates.frontend_developer' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'custom_rates.fullstack_developer' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'custom_rates.ui_ux_designer' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'custom_rates.project_manager' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'custom_rates.qa_engineer' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'custom_rates.devops_engineer' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'custom_rates.business_analyst' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'custom_rates.data_scientist' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'custom_rates.mobile_developer' => ['nullable', 'numeric', 'min:10', 'max:500'],

            // Client information (optional for saving)
            'client_id' => ['nullable', 'exists:clients,id'],
        ]);
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

            // Input method validation
            'input_method.required' => 'Please select an input method.',
            'input_method.in' => 'Input method must be either text or PDF.',

            // PDF validation
            'requirements_file.required' => 'Please upload a PDF file with your requirements.',
            'requirements_file.file' => 'Requirements file must be a valid file.',
            'requirements_file.mimes' => 'Requirements file must be a PDF.',
            'requirements_file.max' => 'Requirements file cannot exceed 10MB.',

            // Text input validation
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

            // Team Configuration messages
            'available_team_size.required' => 'Please specify the available team size.',
            'available_team_size.integer' => 'Team size must be a whole number.',
            'available_team_size.min' => 'Team size must be at least 1 person.',
            'available_team_size.max' => 'Team size cannot exceed 50 people.',
            'work_hours_per_day.required' => 'Please select work hours per day.',
            'work_hours_per_day.in' => 'Work hours must be 4, 6, 8, or 10 hours per day.',
            'target_budget.numeric' => 'Target budget must be a valid number.',
            'target_budget.min' => 'Target budget must be at least $1,000.',
            'target_budget.max' => 'Target budget cannot exceed $10,000,000.',

            // Custom rates messages
            'custom_rates.*.numeric' => 'Hourly rate must be a valid number.',
            'custom_rates.*.min' => 'Hourly rate must be at least $10.',
            'custom_rates.*.max' => 'Hourly rate cannot exceed $500.',

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
            'input_method' => 'input method',
            'requirements_file' => 'requirements file',
            'functional_requirements' => 'functional requirements',
            'technical_requirements' => 'technical requirements',
            'quality_requirements' => 'quality requirements',
            'requirements_quality' => 'requirements quality',
            'desired_quality_level' => 'quality level',
            'team_seniority' => 'team seniority',
            'buffer_percentage' => 'buffer percentage',
            'additional_context' => 'additional context',

            // Team configuration attributes
            'available_team_size' => 'available team size',
            'work_hours_per_day' => 'work hours per day',
            'target_budget' => 'target budget',
            'custom_rates' => 'custom rates',

            'client_id' => 'client',
        ];
    }
}
