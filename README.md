# OrderFo

OrderFo is a Laravel-based food ordering and restaurant management system. It is designed to support both customers and administrators in managing food items, orders, restaurants, delivery areas, and store settings through a simple API-driven architecture.

This project is built for a food marketplace workflow where:

- Customers can register, sign in, manage addresses, browse meals, and place orders.
- Restaurants can manage their menu, categories, and related content.
- Admins can control areas, sliders, settings, restaurant listings, and orders.
- The application exposes separate API modules for front-end users and admin users.

## Project idea in simple words

Think of OrderFo as a restaurant ordering platform similar to a food delivery app. A user can:

1. Browse restaurants and meals
2. Add food to a cart
3. Choose delivery address and payment method
4. Place an order
5. Track the order or communicate through the app

At the same time, an admin can manage:

- restaurants
- meal categories
- available areas
- promotional sliders
- order updates
- app settings

## Main features

- User authentication and authorization
- Address management for delivery
- Restaurant and meal browsing
- Cart and order management
- Admin panel for managing categories, meals, sliders, orders, and settings
- Localization support for multilingual content
- Media support for restaurant and meal images
- Notifications and conversation/chat-like communication features
- API structure separated into Front and Admin modules

## Technology stack

- PHP 8.1
- Laravel 10
- MySQL database
- Laravel UI for authentication views
- Laravel Sanctum / token-based API support
- Spatie packages for permissions, media, and translations
- Vite for frontend asset building

## Project structure

```text
orderfo/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── Admin/         # Admin API endpoints
│   │   │   │   └── Front/         # Customer-facing API endpoints
│   │   └── Middleware/
│   ├── Models/                   # Core business models such as User, Meal, Order, Restaurant
│   ├── Providers/
│   └── ...
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── settings.php
├── public/
├── resources/
├── routes/
│   └── web.php
├── tests/
├── .env.example
├── composer.json
├── package.json
├── phpunit.xml
├── vite.config.js
├── artisan
└── README.md
```

## Key modules

### Front API
The `app/Http/Controllers/Api/Front` folder contains functions for customer actions, such as:

- authentication and login
- cart operations
- order placement
- restaurant and meal browsing
- address management
- conversations and user interactions

### Admin API
The `app/Http/Controllers/Api/Admin` folder contains functions for business management, such as:

- restaurant management
- meal and category management
- area and slider management
- order processing
- system settings

### Models
The project contains domain models like:

- `User`
- `Meal`
- `Order`
- `Resturant`
- `Category`
- `Cart`
- `Address`
- `Setting`
- `Area`
- `Governorate`
- `Review`

These models represent the main entities of the ordering platform.

## Database and data design

The application uses Laravel migrations and a relational database model. The main entities in the system are centered around:

- users
- restaurants
- categories
- meals
- orders
- carts
- addresses
- settings and localization entries

## How the app works

A typical user flow looks like this:

1. User signs up or logs in
2. User opens the app and browses restaurants and meals
3. User adds food to the cart
4. User enters the delivery address
5. User places an order
6. Admin receives and processes the order
7. The app updates order status and keeps settings consistent

## Setup instructions

### 1. Clone the project

```bash
git clone https://github.com/basmasamir469/orderfo.git
cd orderfo
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create environment file

```bash
cp .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Configure the database

Update your `.env` file with your database credentials, then run:

```bash
php artisan migrate
```

You can also seed the database if required by your environment.

### 6. Install frontend dependencies

```bash
npm install
npm run build
```

### 7. Run the project

```bash
php artisan serve
```

Then open the app in your browser at:

```text
http://localhost:8000
```

## Notes for understanding the project

This repo is not a simple starter Laravel app. It is a working food-ordering application with a backend structure designed around business features. The main important folders for a new developer are:

- `app/Http/Controllers/Api/Front` → customer actions
- `app/Http/Controllers/Api/Admin` → admin actions
- `app/Models` → application data models
- `database/migrations` → database schema design
- `routes/web.php` → web routes

## Why this project matters

OrderFo is a practical example of a full-stack Laravel application built around real business logic. It combines:

- backend API logic
- database modeling
- order management
- multilingual support
- admin operations
- user-facing features

This makes it a useful project for learning how a real food delivery application can be structured in Laravel.
