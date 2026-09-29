# AI Price Compare 🤖📊

**AI Price Compare** is a comprehensive platform designed to discover, compare, analyze, and track pricing, feature matrices, deals, and ROI across artificial intelligence tools and SaaS subscriptions.

---

## 🌟 Key Features

### 🔍 Discovery & Comparison
- **AI Tool Directory & Search**: Browse curated AI products by categories (Image Generators, Code Assistants, LLMs, Video Generators, etc.).
- **Side-by-Side Comparison (`/compare`)**: Compare features, pricing tiers, limitations, and plans side-by-side.
- **AI Tool Finder (`/finder`)**: Interactive recommendation engine to find the right AI tool for your specific workflow.
- **ROI & Savings Calculator (`/calculator`)**: Estimate cost savings and return on investment when switching or subscribing to AI services.

### 💰 Deals & Global Pricing
- **Deals & Discounts (`/deals`)**: Discover active promos, coupons, and discount subscriptions.
- **Multi-Country Support & Currency Switching**: View prices localized to different geographical regions.
- **Price History Tracking**: Monitor pricing fluctuations and plan updates over time.

### 🛠️ Dedicated AI Tools
- **Image-to-Video Generator / Playground (`/image-to-video`)**
- **Image-to-Image Transformation (`/image-to-image`)**

### 🛡️ Admin Dashboard (`/admin`)
- **Product & Plan Management**: Manage AI products, subscription tiers, and pricing structures.
- **Feature Matrix Editor**: Dynamically map capabilities across tools.
- **Affiliate & Outbound Link Tracker**: Track outbound referral clicks and conversion analytics.
- **Price History & Regional Controls**: Oversee historical data and regional price multipliers.

---

## 🛠️ Technology Stack

- **Backend Framework**: [Laravel 12 / PHP 8.3+](https://laravel.com)
- **Frontend / Views**: Blade Components, Vanilla CSS, JavaScript
- **Database**: SQLite (Default local) / MySQL / PostgreSQL
- **Bundler**: Vite
- **Asset / Package Management**: Composer & NPM

---

## 🚀 Quick Start Guide

### Prerequisites
- PHP 8.3 or higher
- Composer
- Node.js & NPM

### 1. Clone & Set Up Dependencies
```bash
git clone <repository-url>
cd ai-price-compare

composer install
npm install
```

### 2. Environment Configuration
Copy `.env.example` (or set up `.env`) and generate your application key:
```bash
cp .env.example .env # if applicable
php artisan key:generate
```

Ensure standard `.env` settings:
```env
APP_NAME="AI Price Compare"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
```

### 3. Database Migration & Seeding
Prepare the SQLite database and seed initial data:
```bash
touch database/database.sqlite
php artisan migrate --seed
```

### 4. Build Frontend & Launch
Run dev server and asset compilation:
```bash
npm run dev
# In a separate terminal:
php artisan serve
```
Visit `http://localhost:8000` in your browser.

---

## 📂 Project Structure

```
ai-price-compare/
├── app/
│   ├── Http/Controllers/    # Frontend & Admin Controllers
│   │   ├── Admin/           # Product, Pricing, Plan, Link Admin Controllers
│   │   ├── CompareController.php
│   │   ├── AiFinderController.php
│   │   └── RoiCalculatorController.php
│   └── Models/              # Product, Plan, Price, Feature, Click, Country models
├── config/                  # App, database, and service configuration files
├── database/                # Migrations, seeders, factories, SQLite storage
├── public/                  # Public assets, entry point
├── resources/               # Blade views, CSS, JS source files
└── routes/                  # Web & Admin routes (routes/web.php)
```

---

## ⚙️ Useful Commands

| Action | Command |
| :--- | :--- |
| **Run Dev Server** | `php artisan serve` |
| **Clear App Cache** | `php artisan config:clear` |
| **Run Migrations** | `php artisan migrate` |
| **Build Assets** | `npm run build` |
| **Execute Tests** | `php artisan test` |

---

## 📄 License

This project is licensed under the MIT License.
