# Smart Project Estimator

AI-powered tool that automatically analyzes client requirements and generates estimated project costs, timelines, and resource breakdowns.

## 🚀 Features

- **AI-Powered Analysis**: Uses OpenAI GPT-3.5-turbo to analyze project requirements
- **Automatic Feature Extraction**: Extracts key features and complexity from natural language
- **Cost Calculation**: Generates detailed cost breakdowns by role and feature
- **Timeline Estimation**: Calculates project duration based on team size
- **Region-Based Pricing**: Supports multiple regions with cost multipliers
- **Technology Stack Selection**: Choose from popular technologies
- **User Authentication**: Laravel Breeze with React/Inertia.js
- **Beautiful UI**: Tailwind CSS responsive design

## 🛠️ Technology Stack

- **Frontend**: React 18 + Inertia.js + Tailwind CSS
- **Backend**: Laravel 11 + Laravel Breeze
- **Database**: MySQL 8.0+
- **AI**: OpenAI API (GPT-3.5-turbo)
- **Server**: PHP 8.2+

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.2 or higher**
- **Composer**
- **Node.js 18+ and npm**
- **MySQL 8.0+**
- **XAMPP** (or similar with Apache and MySQL)
- **OpenAI API Key** (Get free tier at https://platform.openai.com/)

## 🔧 Installation

### Step 1: Start XAMPP Services

1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service

### Step 2: Create Database

```bash
# Open MySQL command line or phpMyAdmin
mysql -u root -p

# Create database
CREATE DATABASE smart_estimator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Step 3: Configure Environment

```bash
# Navigate to project directory
cd D:/xampp/php-8-2-0/htdocs/hackathon

# Copy environment file (already done)
# cp .env.example .env

# Open .env file and update:
# 1. Set your OpenAI API key
OPENAI_API_KEY=sk-your-actual-openai-key-here

# 2. Verify database settings
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_estimator
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 5: Run Migrations and Seeders

```bash
# Generate application key (if not set)
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database with initial data
php artisan db:seed
```

### Step 6: Build Frontend Assets

```bash
# For development (with hot reload)
npm run dev

# For production
npm run build
```

### Step 7: Start Development Server

```bash
# In a new terminal window
php artisan serve
```

The application will be available at: **http://localhost:8000**

## 🎯 Usage

### 1. Register/Login

- Visit http://localhost:8000
- Click "Register" to create a new account
- Or login with test credentials if seeded

### 2. Create New Estimate

1. Click **"New Estimate"** from Dashboard or Estimates page
2. Fill in the form:
   - **Project Name**: Name of your project
   - **Requirements**: Describe your project in detail (minimum 50 characters)
   - **Region**: Select geographic region for pricing
   - **Team Size**: Number of team members (1-50)
   - **Technologies**: Optional - select preferred tech stack

3. Click **"Generate Estimate"**

### 3. View Estimate Results

The AI will analyze your requirements and display:
- **Total Cost**: Estimated project cost
- **Hours**: Total development hours
- **Timeline**: Project duration in days
- **Complexity**: Overall complexity level
- **Feature Breakdown**: List of extracted features with hours
- **Team Composition**: Recommended team structure
- **Cost Breakdown**: Detailed costs by role

### 4. Manage Estimates

- View all estimates in **"My Estimates"** page
- Click on any estimate to see full details
- Delete estimates you no longer need

## 🤖 AI Configuration

### OpenAI API Key Setup

1. Visit https://platform.openai.com/
2. Create an account or login
3. Go to API Keys section
4. Create a new API key
5. Copy the key to your `.env` file:

```env
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxx
```

### Free Tier Limits

- **GPT-3.5-turbo**: ~3 requests per minute
- **Token Limit**: 4096 tokens per request
- The application includes fallback logic if API fails

## 📂 Project Structure

```
hackathon/
├── app/
│   ├── Http/Controllers/
│   │   ├── EstimateController.php
│   │   ├── RegionController.php
│   │   └── TechnologyController.php
│   ├── Models/
│   │   ├── Estimate.php
│   │   ├── Region.php
│   │   ├── TeamRole.php
│   │   ├── Technology.php
│   │   ├── Requirement.php
│   │   ├── EstimateBreakdown.php
│   │   └── PricingTier.php
│   ├── Policies/
│   │   └── EstimatePolicy.php
│   └── Services/
│       ├── OpenAIService.php
│       └── EstimationEngine.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── RegionSeeder.php
│       ├── TeamRoleSeeder.php
│       └── TechnologySeeder.php
├── resources/
│   └── js/
│       └── Pages/
│           ├── Dashboard.jsx
│           └── Estimates/
│               ├── Index.jsx
│               ├── Create.jsx
│               └── Show.jsx
└── routes/
    └── web.php
```

## 🗄️ Database Schema

### Tables

1. **users** - User authentication
2. **estimates** - Project estimates
3. **requirements** - Extracted features
4. **regions** - Geographic regions with pricing
5. **team_roles** - Developer roles (Frontend, Backend, etc.)
6. **technologies** - Available tech stack options
7. **pricing_tiers** - Region-specific role pricing
8. **estimate_breakdowns** - Cost breakdown by role

## 🎨 Features Explained

### AI Analysis Process

1. **Input**: User provides project requirements in natural language
2. **OpenAI Processing**: GPT-3.5-turbo analyzes and extracts:
   - List of features
   - Complexity levels
   - Estimated hours per feature
   - Recommended team composition
   - Technology suggestions
3. **Cost Calculation**: Backend calculates costs using:
   - Base hourly rates per role
   - Region cost multipliers
   - Complexity multipliers
4. **Timeline Generation**: Estimates project duration based on total hours and team size

### Pricing Model

```
Cost = Σ(Feature Hours × Role Rate × Complexity × Region Multiplier)
Timeline = Total Hours / (Team Size × Efficiency Factor)
```

**Complexity Multipliers:**
- Simple: 1.0x
- Medium: 1.5x
- Complex: 2.5x
- Very Complex: 4.0x

**Example Regions:**
- North America: 1.5x
- Western Europe: 1.4x
- Eastern Europe: 0.8x
- South Asia: 0.5x

## 🔍 Troubleshooting

### MySQL Connection Error

```bash
# Check if MySQL is running in XAMPP
# Start MySQL service from XAMPP Control Panel

# Verify database exists
mysql -u root -e "SHOW DATABASES;"

# Recreate if needed
mysql -u root -e "CREATE DATABASE smart_estimator;"
```

### OpenAI API Errors

```bash
# Check your API key is valid
# Verify you have credits in your OpenAI account
# The app has fallback logic if API fails
```

### Vite/Node Errors

```bash
# Clear npm cache
npm cache clean --force

# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install

# Rebuild assets
npm run build
```

### Migration Errors

```bash
# Reset database
php artisan migrate:fresh

# Reseed data
php artisan db:seed
```

## 🚀 Deployment

### Production Build

```bash
# Build frontend assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Variables for Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use strong app key
php artisan key:generate

# Production database
DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Your OpenAI key
OPENAI_API_KEY=sk-your-key
```

## 📝 Code Quality Standards

- ✅ Clean, self-documenting code
- ✅ No unnecessary comments
- ✅ Proper error handling
- ✅ Input validation
- ✅ Authorization policies
- ✅ Responsive design
- ✅ TypeScript-style React components

## 🔐 Security

- CSRF protection enabled
- Input sanitization
- Authorization policies
- Environment variables for secrets
- Password hashing with bcrypt
- SQL injection protection via Eloquent ORM

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run specific test
php artisan test --filter EstimateTest
```

## 📚 API Endpoints

### Public Routes
- `GET /` - Welcome page
- `GET /login` - Login page
- `POST /login` - Authenticate user
- `GET /register` - Registration page
- `POST /register` - Create new user

### Protected Routes (Requires Authentication)
- `GET /dashboard` - User dashboard
- `GET /estimates` - List all estimates
- `GET /estimates/create` - Create estimate form
- `POST /estimates` - Store new estimate
- `GET /estimates/{id}` - View estimate details
- `DELETE /estimates/{id}` - Delete estimate
- `GET /api/technologies` - Get technology list
- `GET /api/regions` - Get regions list

## 🎓 Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [React Documentation](https://react.dev/)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Tailwind CSS Documentation](https://tailwindcss.com/)
- [OpenAI API Documentation](https://platform.openai.com/docs)

## 🤝 Contributing

This is a hackathon project. Feel free to fork and enhance!

## 📄 License

Open source - MIT License

## 👨‍💻 Author

Created for Hackathon Project

## 🎉 Acknowledgments

- OpenAI for GPT-3.5-turbo API
- Laravel Team for the amazing framework
- React Team for the UI library
- Tailwind Labs for the CSS framework

---

## 📞 Support

For issues or questions:
1. Check the Troubleshooting section
2. Review the documentation
3. Check Laravel/React/Inertia.js documentation

**Happy Estimating! 🚀**

