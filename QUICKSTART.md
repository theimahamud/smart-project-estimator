# ✅ LAUNCH CHECKLIST - Copy & Paste These Commands

## 🎯 Quick Launch (3 Steps)

### Step 1: Start XAMPP
```
☐ Open XAMPP Control Panel
☐ Click "Start" next to Apache
☐ Click "Start" next to MySQL
```

### Step 2: Setup Database (Copy & Paste)
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS smart_estimator;"
```

### Step 3: Add OpenAI API Key
```bash
# Edit: D:\xampp\php-8-2-0\htdocs\hackathon\.env
# Find line: OPENAI_API_KEY=your_openai_api_key_here
# Replace with: OPENAI_API_KEY=sk-your-actual-key

# Get free key from: https://platform.openai.com/api-keys
```

---

## 🚀 Terminal 1 Commands (Backend)

```bash
cd D:/xampp/php-8-2-0/htdocs/hackathon

php artisan migrate:fresh

php artisan db:seed

php artisan serve
```

**Wait for:** `Server running on [http://127.0.0.1:8000]`

---

## 🎨 Terminal 2 Commands (Frontend)

```bash
cd D:/xampp/php-8-2-0/htdocs/hackathon

npm run dev
```

**Wait for:** `VITE ready`

---

## 🌐 Open Browser

Visit: **http://localhost:8000**

---

## ✅ Done!

Your Smart Project Estimator is now running!

**Next:** 
1. Click "Register" to create account
2. Click "New Estimate" to test
3. Enter project requirements
4. See AI-powered results!

---

## 🐛 Quick Fixes

### MySQL Won't Start?
```bash
# Check port 3306 is not in use
# Restart XAMPP
# Try different port in .env: DB_PORT=3307
```

### Port 8000 Taken?
```bash
php artisan serve --port=8080
# Visit: http://localhost:8080
```

### OpenAI Error?
```
# App has fallback - will still work!
# To fix: Add valid API key to .env
```

---

**That's it! 🎉**

