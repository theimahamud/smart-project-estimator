# AI Training Guide - Smart Project Estimator

## 🤖 Guide for AI Agents (Claude, GPT, etc.)

This guide helps AI agents understand how to work with this codebase for future development and modifications.

---

## 📁 Project Architecture

### Framework: Laravel 11 + React (Inertia.js)
- **Backend**: Laravel handles routing, business logic, database, and API
- **Frontend**: React components with Inertia.js (no separate API needed)
- **Styling**: Tailwind CSS utility-first framework
- **Authentication**: Laravel Breeze with React stack

### Key Concept: Inertia.js
- Server-rendered React components
- No need for separate REST API endpoints for pages
- Controllers return Inertia::render() instead of JSON
- React components receive props directly from controllers

---

## 🎯 Core Functionality Flow

### 1. Estimate Creation Flow

**Request Path:**
```
User Input → EstimateController@store 
→ OpenAIService@analyzeRequirements 
→ EstimationEngine@calculateEstimate 
→ Database Storage 
→ Inertia Response
```

**Files Involved:**
- `app/Http/Controllers/EstimateController.php`
- `app/Services/OpenAIService.php`
- `app/Services/EstimationEngine.php`
- `app/Models/Estimate.php`
- `resources/js/Pages/Estimates/Create.jsx`

### 2. AI Analysis Process

**OpenAIService Logic:**
```php
1. Build prompt with system instructions
2. Call OpenAI GPT-3.5-turbo API
3. Parse JSON response
4. Fallback to rule-based if API fails
5. Return structured data array
```

**Expected Response Structure:**
```json
{
    "features": [
        {
            "name": "Feature Name",
            "complexity": "medium",
            "hours": 40,
            "description": "Description"
        }
    ],
    "team_composition": {
        "Frontend Developer": 1,
        "Backend Developer": 1
    },
    "technologies": ["React", "Laravel"],
    "total_hours": 200,
    "complexity_level": "medium",
    "risks": ["Risk 1", "Risk 2"]
}
```

### 3. Cost Calculation Logic

**EstimationEngine Process:**
```php
1. Loop through AI-extracted features
2. Create Requirement records
3. Calculate hours per team role
4. Get hourly rates (region-adjusted)
5. Create EstimateBreakdown records
6. Calculate total cost and timeline
7. Update Estimate model
```

**Formula:**
```
Role Cost = Hours × (Base Rate × Region Multiplier)
Total Cost = Σ(All Role Costs)
Timeline = Total Hours / (Team Size × 0.8 efficiency)
```

---

## 🛠️ How to Modify/Extend

### Adding a New Feature to Estimates

**Step 1: Add Database Column**
```bash
php artisan make:migration add_field_to_estimates_table
```

**Step 2: Update Model**
```php
// app/Models/Estimate.php
protected $fillable = [
    'existing_fields',
    'new_field', // Add here
];
```

**Step 3: Update Controller**
```php
// app/Http/Controllers/EstimateController.php
public function store(Request $request) {
    $validated = $request->validate([
        'new_field' => 'required|string',
    ]);
}
```

**Step 4: Update React Component**
```jsx
// resources/js/Pages/Estimates/Create.jsx
const { data, setData } = useForm({
    new_field: '',
});
```

### Adding a New Service

**Example: PDF Export Service**

**Step 1: Create Service Class**
```bash
touch app/Services/PdfExportService.php
```

**Step 2: Implement Service**
```php
namespace App\Services;

class PdfExportService {
    public function exportEstimate(Estimate $estimate): string {
        // Implementation
    }
}
```

**Step 3: Use in Controller**
```php
use App\Services\PdfExportService;

public function __construct(
    private PdfExportService $pdfService
) {}

public function export(Estimate $estimate) {
    $pdf = $this->pdfService->exportEstimate($estimate);
    return response()->download($pdf);
}
```

### Adding a New React Page

**Step 1: Create Component File**
```bash
touch resources/js/Pages/Estimates/Edit.jsx
```

**Step 2: Create Component**
```jsx
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Edit({ estimate }) {
    const { data, setData, put } = useForm({
        project_name: estimate.project_name,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('estimates.update', estimate.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Edit Estimate" />
            {/* Form here */}
        </AuthenticatedLayout>
    );
}
```

**Step 3: Add Controller Method**
```php
public function edit(Estimate $estimate) {
    $this->authorize('update', $estimate);
    return Inertia::render('Estimates/Edit', [
        'estimate' => $estimate,
    ]);
}
```

**Step 4: Add Route**
```php
// routes/web.php
Route::get('/estimates/{estimate}/edit', [EstimateController::class, 'edit'])
    ->name('estimates.edit');
```

---

## 🔍 Code Patterns to Follow

### 1. Controller Pattern
```php
class ExampleController extends Controller {
    public function __construct(
        private ServiceClass $service
    ) {}

    public function index() {
        $data = Model::with('relations')->paginate(10);
        return Inertia::render('Page/Index', ['data' => $data]);
    }

    public function store(Request $request) {
        $validated = $request->validate([/* rules */]);
        $model = Model::create($validated);
        return redirect()->route('route.name', $model)
            ->with('success', 'Message');
    }
}
```

### 2. Service Pattern
```php
class ExampleService {
    public function process($input): array {
        try {
            // Business logic
            return ['success' => true, 'data' => $result];
        } catch (\Exception $e) {
            // Fallback or error handling
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
```

### 3. Model Pattern
```php
class Example extends Model {
    protected $fillable = ['field1', 'field2'];
    protected $casts = ['json_field' => 'array'];

    public function relation() {
        return $this->belongsTo(Related::class);
    }
}
```

### 4. React Component Pattern
```jsx
export default function Component({ propFromController }) {
    const { data, setData, post, processing, errors } = useForm({
        field: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('route.name'));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Page Title" />
            <div className="py-12">
                {/* Content */}
            </div>
        </AuthenticatedLayout>
    );
}
```

---

## 🚫 Code Quality Rules

### DO:
✅ Use dependency injection in controllers
✅ Keep controllers thin, move logic to services
✅ Use form requests for complex validation
✅ Return Inertia::render() for pages
✅ Use route() helper in React components
✅ Follow PSR-12 coding standards (PHP)
✅ Use meaningful variable names
✅ Handle errors gracefully
✅ Use Eloquent relationships
✅ Validate all user inputs

### DON'T:
❌ Write business logic in controllers
❌ Use raw SQL queries (use Eloquent)
❌ Return JSON from page controllers (use Inertia)
❌ Hardcode values (use config files)
❌ Ignore validation errors
❌ Write unnecessary comments
❌ Use inline styles (use Tailwind classes)
❌ Expose sensitive data in responses
❌ Skip authorization checks

---

## 🗃️ Database Relationships

```php
User → hasMany → Estimates
Estimate → belongsTo → User
Estimate → belongsTo → Region
Estimate → hasMany → Requirements
Estimate → hasMany → EstimateBreakdowns

EstimateBreakdown → belongsTo → TeamRole
TeamRole → hasMany → PricingTiers
Region → hasMany → PricingTiers
PricingTier → belongsTo → Region
PricingTier → belongsTo → TeamRole
```

---

## 🔐 Security Considerations

### Authorization
```php
// Use policies for all resource actions
$this->authorize('view', $estimate);
$this->authorize('update', $estimate);
$this->authorize('delete', $estimate);
```

### Validation
```php
$request->validate([
    'field' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'number' => 'integer|min:1|max:100',
]);
```

### Mass Assignment Protection
```php
// Always define $fillable in models
protected $fillable = ['allowed', 'fields', 'only'];
```

---

## 🧪 Testing Strategy

### Feature Tests
```php
public function test_user_can_create_estimate() {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('/estimates', [
        'project_name' => 'Test Project',
        'raw_requirements' => 'Test requirements...',
    ]);
    $response->assertRedirect();
    $this->assertDatabaseHas('estimates', [
        'project_name' => 'Test Project',
    ]);
}
```

---

## 📚 Useful Commands

### Development
```bash
php artisan serve              # Start Laravel server
npm run dev                    # Start Vite dev server
php artisan migrate            # Run migrations
php artisan db:seed            # Seed database
php artisan route:list         # List all routes
php artisan make:model Model   # Create model
php artisan make:controller C  # Create controller
```

### Debugging
```bash
php artisan tinker             # Interactive shell
php artisan log:clear          # Clear logs
tail -f storage/logs/laravel.log  # Watch logs
```

### Production
```bash
npm run build                  # Build assets
php artisan config:cache       # Cache config
php artisan route:cache        # Cache routes
php artisan view:cache         # Cache views
```

---

## 🎓 Learning Resources

- **Inertia.js**: How React integrates with Laravel
- **Laravel Eloquent**: ORM and relationships
- **React Hooks**: useState, useForm (Inertia)
- **Tailwind CSS**: Utility classes
- **OpenAI API**: GPT-3.5-turbo usage

---

## 💡 AI Agent Instructions

When making changes to this codebase:

1. **Understand the context**: Read relevant files first
2. **Follow patterns**: Match existing code style
3. **Update all layers**: Model → Controller → View
4. **Test changes**: Run migrations, check browser
5. **Handle errors**: Add try-catch, validation
6. **Document**: Update README if adding features
7. **Clean code**: No unnecessary comments
8. **Security**: Always validate, authorize, sanitize

**Example Task: "Add export to PDF feature"**

**Steps:**
1. Create Service: `app/Services/PdfExportService.php`
2. Install package: `composer require barryvdh/laravel-dompdf`
3. Add controller method: `EstimateController@export`
4. Add route: `GET /estimates/{estimate}/export`
5. Add button in React: `Show.jsx`
6. Test functionality
7. Update README with new feature

---

## 🔄 Common Modification Scenarios

### Scenario 1: Change Complexity Multipliers
**File**: `app/Services/EstimationEngine.php`
```php
private $complexityMultipliers = [
    'simple' => 1.0,    // Change these values
    'medium' => 1.5,
    'complex' => 2.5,
    'very_complex' => 4.0,
];
```

### Scenario 2: Add New Region
**Command**: Add to seeder or use admin interface
```php
Region::create([
    'name' => 'Africa',
    'code' => 'AF',
    'cost_multiplier' => 0.6,
]);
```

### Scenario 3: Customize AI Prompt
**File**: `app/Services/OpenAIService.php`
```php
private function getSystemPrompt(): string {
    return "Your custom instructions here...";
}
```

### Scenario 4: Add New Technology Category
**File**: `database/seeders/TechnologySeeder.php`
```php
Technology::create([
    'name' => 'GraphQL',
    'category' => 'API',
    'description' => 'Query language',
    'complexity_multiplier' => 1.2,
]);
```

---

**This guide enables AI agents to understand and modify the codebase effectively! 🤖✨**

