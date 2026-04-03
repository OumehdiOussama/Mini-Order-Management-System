# Mini Order Management System

A simple Laravel application for managing customer orders from creation until final delivery status.
This is an intern project designed to teach core Laravel development concepts.

## Project Goal

Build a Laravel system to manage customer orders from creation until final delivery/cancellation, with clear status tracking and basic reporting.

## Features

### Customer Management

- Create new customer records (name, email, phone)
- View all customers (with pagination)
- Search customers by name or email
- Edit customer details
- Delete customers

### Product Management

- Create product records (name, price)
- View all products
- Edit product details
- Delete products
- Product validation enforces `price >= 1`

### Order Management

- Create orders linked to a specific customer
- Add multiple products with quantities to each order
- Business rule check: quantity must be greater than 0 for selected products
- Status tracking with: `pending`, `processing`, `shipped`, `delivered`, `cancelled`
- Status workflow validation (prevents invalid jumps)
- Shipping fields (`tracking_number`, `carrier`) required when status is set to `shipped`
- Chronological order timeline with timestamped status changes
- View orders with current status, customer info, and total price
- Update order status
- Delete orders

### Basic Reporting (Dashboard)

- Total number of orders
- Number of orders by status
- 5 most recent orders
- Total number of customers
- Total number of products

## Technology Stack

- Framework: Laravel 12
- Database: MySQL
- Frontend: Blade Templates + Tailwind CSS
- PHP Version: 8.2+

## Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL Server
- Node.js and npm

## Installation & Setup

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Create Environment File

Linux/macOS:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 3. Configure Database in `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=order_management
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. (Optional) Seed Demo Data

```bash
php artisan db:seed
```

### 7. Run the Application

Terminal 1:

```bash
npm run dev
```

Terminal 2:

```bash
php artisan serve
```

Application URL:

`http://127.0.0.1:8000`

## Main Routes

- `/` (Dashboard)
- `/dashboard`
- `/customers`
- `/products`
- `/orders`

## Validation Rules

- Customers: name required, email required + unique, phone required
- Products: name required, price required, numeric, minimum 1
- Orders: customer required, products array required, each selected product must have quantity > 0
- Order status: must be one of pending, processing, shipped, delivered, cancelled + workflow transition check
- Shipping fields: required when updating status to shipped

## Notes

- Eloquent relationships implemented:
  - `Customer` has many `Order`
  - `Order` belongs to `Customer`
  - `Order` belongs to many `Product` with pivot `quantity`
  - `Order` has many `OrderTimeline`
- Order timeline is displayed in chronological order.
- Application database is MySQL (configured in `.env` / `.env.example`).

## License

This project is open-source and available under the MIT license.
