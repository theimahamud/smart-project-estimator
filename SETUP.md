# Quick Setup Guide - Smart Project Estimator

## ⚡ Fast Setup (5 Minutes)

### 1. Start XAMPP Services ✅
```bash
# Open XAMPP Control Panel
# Click "Start" for Apache
# Click "Start" for MySQL
```

### 2. Create Database ✅
```bash
mysql -u root
CREATE DATABASE smart_estimator;
EXIT;
```

### 3. Configure OpenAI API Key ✅
```bash
# Edit .env file
# Replace this line:
OPENAI_API_KEY=your_openai_api_key_here

# With your actual key:
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxx
```

**Get Free OpenAI API Key:**
- Go to: https://platform.openai.com/api-keys
- Sign up/Login
- Click "Create new secret key"
- Copy and paste into .env

### 4. Install Dependencies ✅
```bash
cd D:/xampp/php-8-2-0/htdocs/hackathon

# Install PHP packages
composer install

# Install Node packages
npm install
```

### 5. Setup Database ✅
```bash
# Run migrations
php artisan migrate

# Seed initial data (regions, roles, technologies)
php artisan db:seed
```

### 6. Start Application ✅
```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```

### 7. Access Application ✅
Open browser: **http://localhost:8000**

---

## 🎯 First Time Use

1. **Register Account**
   - Click "Register" 
   - Fill in Name, Email, Password
   - Click "Register"

2. **Create First Estimate**
   - Click "New Estimate" button
   - Enter Project Name: "E-commerce Website"
   - Enter Requirements (example):
   ```
   I need an e-commerce platform with user registration, product catalog,
   shopping cart, payment integration with Stripe, order management,
   admin dashboard for managing products and orders, email notifications,
   and customer reviews system.
   ```
   - Select Region: "North America"
   - Set Team Size: 3
   - Select Technologies: React, Laravel, MySQL, Tailwind CSS
   - Click "Generate Estimate"

3. **View Results**
   - See total cost, hours, and timeline
   - Review feature breakdown
   - Check team composition
   - Analyze cost breakdown by role

---

## 🐛 Common Issues

### Issue: MySQL Connection Refused
**Solution:**
```bash
# Check MySQL is running in XAMPP
# Restart MySQL service
# Verify database exists:
mysql -u root -e "SHOW DATABASES;"
```

### Issue: OpenAI API Error
**Solution:**
```bash
# Check your API key is correct in .env
# Verify you have credits: https://platform.openai.com/usage
# Free tier has rate limits (3 requests/min)
```

### Issue: npm/Vite Errors
**Solution:**
```bash
# Clear cache and reinstall
npm cache clean --force
rm -rf node_modules
npm install
npm run dev
```

### Issue: Migration Errors
**Solution:**
```bash
# Reset database
php artisan migrate:fresh
php artisan db:seed
```

---

## 📊 Seeded Data

After running `php artisan db:seed`, you'll have:

**Regions:**
- North America (1.5x multiplier)
- Western Europe (1.4x)
- Eastern Europe (0.8x)
- Asia Pacific (0.7x)
- South Asia (0.5x)
- Latin America (0.6x)
- Middle East (0.9x)

**Team Roles:**
- Frontend Developer ($60/hr base)
- Backend Developer ($65/hr base)
- Full Stack Developer ($70/hr base)
- UI/UX Designer ($55/hr base)
- DevOps Engineer ($75/hr base)
- QA Engineer ($50/hr base)
- Project Manager ($80/hr base)
- Mobile Developer ($65/hr base)

**Technologies:**
- Frontend: React, Vue.js, Angular, Next.js, Tailwind CSS
- Backend: Laravel, Node.js, Django, Express.js
- Database: MySQL, PostgreSQL, MongoDB, Redis
- DevOps: Docker, Kubernetes, GitHub Actions
- AI/ML: OpenAI API, TensorFlow, LangChain

---

## 🔑 Test Credentials

Create your own during registration, or seed a test user:

Edit `database/seeders/DatabaseSeeder.php` and uncomment:
```php
User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
]);
```

Then run: `php artisan db:seed`

**Login:**
- Email: test@example.com
- Password: password

---

## 💡 Tips for Better Estimates

1. **Be Detailed**: More details = better estimates
2. **Specify Features**: List all required features clearly
3. **Mention Integrations**: Third-party APIs, payment gateways, etc.
4. **Include Platforms**: Web, mobile, desktop, etc.
5. **Note Complexity**: Real-time features, AI/ML, complex algorithms

**Example Good Requirement:**
```
Build a task management application with:
- User authentication (email/password and Google OAuth)
- Create, edit, delete tasks with due dates and priorities
- Team collaboration with task assignment
- Real-time notifications using WebSockets
- File attachments up to 10MB per task
- Dashboard with analytics and charts
- Mobile responsive design
- Export tasks to PDF and CSV
- Integration with Google Calendar
- Email reminders for due tasks
```

---

## 🚀 Next Steps

1. ✅ Complete setup steps above
2. ✅ Create your first estimate
3. ✅ Explore different regions and technologies
4. ✅ Review the feature breakdown
5. ✅ Check the detailed README.md for advanced usage

---

## 📞 Need Help?

1. Check README.md for detailed documentation
2. Review troubleshooting section above
3. Verify all services are running (Apache, MySQL)
4. Check terminal for error messages
5. Ensure .env configuration is correct

**Ready to estimate projects with AI! 🎉**

