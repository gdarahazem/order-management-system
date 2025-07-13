# Order Management System API

A RESTful API for managing products and orders built with Laravel 12 and Laravel Sanctum for authentication.

## 🛠 Technical Stack

- **Framework:** Laravel 12
- **Authentication:** Laravel Sanctum (Bearer Token)
- **Database:** MySQL
- **Testing:** PHPUnit Feature Tests
- **API Documentation:** Swagger/OpenAPI
- **Architecture:** Repository/Service Pattern

## 🚀 Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+

### Setup

```bash
# 1. Clone repository
git clone https://github.com/gdarahazem/order-management-system.git
cd order-management-system

# 2. Install dependencies
composer install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=order_management
DB_USERNAME=root
DB_PASSWORD=your_password

# 5. Run migrations
php artisan migrate

# 6. Start server
php artisan serve
```

## 📋 API Endpoints

### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/register` | Register new user |
| POST | `/api/auth/login` | Login user |
| POST | `/api/auth/logout` | Logout user |

### Products (Protected)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/products` | Get all products |
| POST | `/api/products` | Create new product |

### Orders (Protected)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/orders` | Get all orders with product details |
| POST | `/api/orders` | Create new order |

## 🔐 Authentication

This API uses **Laravel Sanctum** for token-based authentication. All product and order endpoints require **Bearer Token** authentication.

### Quick Start
```bash
# 1. Register user
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123","password_confirmation":"password123"}'

# 2. Get token from response and use in other requests
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 📱 Postman Collection

The project includes a **complete Postman collection** for easy API testing.

### Setup Postman Environment

1. **Import Collection:**
   - Import `postman_collection.json` from project root
   - Collection includes all endpoints with automatic token management

2. **Create Environment:**
   - **Environment Name:** `Order Management API`
   - **Variables:**
     ```
     base_url: http://localhost:8000/api
     auth_token: (put the token returned by login)
     ```

## 🧪 Testing

Run the feature tests:

```bash
# Run all tests
php artisan test

# Run specific test files
php artisan test tests/Feature/AuthApiTest.php
php artisan test tests/Feature/ProductApiTest.php
php artisan test tests/Feature/OrderApiTest.php
```

**Test Coverage:**
- ✅ User Registration & Login
- ✅ Product Management (GET/POST)
- ✅ Order Management (GET/POST)
- ✅ Authentication & Validation

## 📚 API Documentation

**Swagger Documentation:** `http://localhost:8000/api/documentation`

Interactive API documentation with:
- All endpoints documented
- Request/response examples
- Built-in authentication
- Try-it-out functionality


## 🔧 API Usage Examples

### Create Product
```json
POST /api/products
{
    "name": "Gaming Laptop",
    "price": 1299.99
}
```

### Create Order
```json
POST /api/orders
{
    "products": [1, 2, 3]
}
```

**Response includes:**
- Product IDs array
- **Auto-calculated total price**
- Complete product details

## 📄 Project Structure

```
app/
├── Http/Controllers/Api/     # API Controllers
├── Services/                 # Business Logic
├── Repositories/            # Data Access Layer
├── Http/Requests/           # Validation
└── Traits/                  # Reusable Components

tests/Feature/               # API Integration Tests
```

## 🛠 Troubleshooting

**Clear caches if needed:**
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

**Database issues:**
- Ensure MySQL is running
- Verify database credentials in `.env`
- Run `php artisan migrate`

