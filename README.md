# 🎯 CareerGuide — AI-Powered Career Guidance Platform

A full-stack web application that helps students and professionals discover suitable careers through trait-based psychometric assessment and an AI-powered chatbot.

## ✨ Features
- 20-question trait-based career assessment (8 categories, 64 specialisations)
- AI career chatbot powered by Google Gemini API
- Personalised dashboard — saved results, exams, career interests
- Account management with profile editing
- 52 career options across Engineering, Medical, Business, Creative fields

## 🛠️ Tech Stack
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7 (XAMPP)
- **AI:** Google Gemini API (via secure PHP proxy)

## 🚀 Setup Instructions

### 1. Clone the repository
```bash
git clone https://github.com/YOUR_USERNAME/career-guidance.git
```

### 2. Copy to XAMPP
Copy all files to:
```
C:\xampp\htdocs\career_guidance\
```

### 3. Set up the database
- Open phpMyAdmin → `http://localhost/phpmyadmin`
- Create database: `career_guidance`
- Run the schema: import `career_guidance_schema.sql`

### 4. Configure your API key
```bash
# Copy the example file
cp .env.example .env

# Open .env and paste your Gemini API key
GEMINI_API_KEY=your_actual_key_here
```
Get a free Gemini API key at: https://aistudio.google.com/app/apikey

### 5. Start XAMPP
- Start **Apache** and **MySQL** in XAMPP Control Panel
- Visit: `http://localhost/career_guidance/`

## 🔐 Security Note
Your `.env` file is listed in `.gitignore` and will **never** be uploaded to GitHub.  
Never hardcode your API key directly in `ai_proxy.php`.

## 📁 Project Structure
```
career_guidance/
├── index.html              # Homepage with career browse
├── tests.html              # Career assessment engine
├── results.html            # Assessment results page
├── dashboard.html          # Personal dashboard
├── aibot.html              # AI career chatbot
├── account.html            # Profile management
├── login.html              # Login page
├── register_form.html      # Registration page
├── ai_proxy.php            # Gemini API proxy (key loaded from .env)
├── config.php              # Database connection
├── save_result.php         # Save assessment results
├── get_results.php         # Fetch results
├── save_exam.php           # Save entrance exams
├── get_exams.php           # Fetch exams
├── save_interest.php       # Save career interests
├── get_interests.php       # Fetch interests
├── fetch_user.php          # Get user profile
├── update_profile.php      # Update profile
├── change_password.php     # Change password
├── login.php               # Login handler
├── register.php            # Register handler
├── career_guidance_schema.sql  # Database schema
├── .env.example            # API key template (copy to .env)
├── .env                    # Your real API key — NEVER commit this ❌
└── .gitignore              # Tells Git to ignore .env
```

## 👩‍💻 Author
Jenny Jain — MIT Art Design & Technology University, Pune
