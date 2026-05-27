# 🛡️ LoanGuard — AI Loan Risk Analyzer

A full-stack web application that uses a custom-trained Machine Learning model to instantly evaluate loan applications and generate risk scores in real time.

Built with **Laravel 13**, **Python Flask**, **scikit-learn**, and **MySQL**.

---

## 🚀 Live Features

- 🤖 **Real AI Risk Scoring** — Custom Logistic Regression model trained on financial data (80.5% accuracy)
- 👥 **Role-Based Access** — Separate dashboards for Admin and Applicant
- 📊 **Admin Dashboard** — Charts, stats, filter/search, approve/reject via AJAX
- 📋 **Applicant Dashboard** — Submit applications, track status, view AI risk scores
- 📄 **PDF Generation** — Download professional loan slip for any application
- 📧 **Email Notifications** — Automated emails on submission and status change
- 🔒 **Policy & Form Request** — Laravel authorization and validation
- 🌱 **Seeders & Factories** — 50 realistic dummy applications seeded
- 📱 **Fully Responsive** — Works on all screen sizes

---

## 🧠 How the AI Works

Applicant submits form
↓
Laravel validates (Form Request)
↓
Laravel sends HTTP POST → Flask /predict
↓
Flask runs Logistic Regression model
↓
Returns { risk_score: 0.65, label: "high" }
↓
Laravel saves to MySQL + shows result

**Features used by the model:**
- Credit Score
- Annual Income
- Loan Amount
- Employment Years
- Age

**Model accuracy: 80.5%** (trained with scikit-learn on synthetic financial data)

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | Laravel Blade + Bootstrap 5 + Chart.js |
| Backend | Laravel 13 (PHP 8.5) |
| AI/ML | Python 3 + Flask + scikit-learn |
| Database | MySQL |
| Email | Laravel Mail + Gmail SMTP |
| PDF | barryvdh/laravel-dompdf |

---

## 📁 Project Structure

loanguard/
├── app/
│   ├── Http/Controllers/
│   │   └── LoanApplicationController.php
│   ├── Mail/
│   │   ├── ApplicationSubmitted.php
│   │   └── ApplicationStatusChanged.php
│   ├── Models/
│   │   ├── LoanApplication.php
│   │   └── User.php
│   ├── Policies/
│   │   └── LoanApplicationPolicy.php
│   └── Http/Requests/
│       └── StoreLoanApplicationRequest.php
├── database/
│   ├── factories/LoanApplicationFactory.php
│   └── seeders/
│       ├── AdminSeeder.php
│       └── LoanApplicationSeeder.php
├── ml/
│   ├── app.py          ← Flask API server
│   ├── train_model.py  ← Model training script
│   ├── model.pkl       ← Trained model
│   └── scaler.pkl      ← Feature scaler
└── resources/views/
├── layouts/app.blade.php
├── applications/
├── admin/
└── emails/

---

## ⚙️ Installation & Setup

### 1. Clone the repository
```bash
git clone https://github.com/malaikaarif/loanguard.git
cd loanguard
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```
Update `.env` with your database and mail credentials.

### 4. Run migrations and seed
```bash
php artisan migrate
php artisan db:seed
```

### 5. Start Laravel server
```bash
php artisan serve
```

### 6. Install Python dependencies and start Flask
```bash
cd ml
pip install flask scikit-learn numpy joblib
python app.py
```

---

## 👤 Default Login Credentials

| Role | Email | Password |
|---|---|---|
| Admin | admin@loanguard.com | password |
| Applicant | applicant1@loanguard.com | password |

---

## 📸 Screenshots

> Landing Page · Admin Dashboard · Applicant Dashboard · Loan Form · PDF Slip · Email Notification

---

## 🎯 Laravel Features Used

- ✅ Eloquent ORM with relationships
- ✅ Policy (LoanApplicationPolicy)
- ✅ Form Request (StoreLoanApplicationRequest)
- ✅ Seeders & Factories
- ✅ Middleware & Route Groups
- ✅ Blade Components & Layouts
- ✅ AJAX + JSON API responses
- ✅ Laravel Mail
- ✅ Resource Controllers

---

## 👩‍💻 Developer

**Malaika Arif**
BS Computer Science — COMSATS University Islamabad 
AI Engineering Track

---

## 📄 License

This project is for academic purposes.