# 🌐 Translation Management Service

A high-performance, scalable API-driven service for managing translations across multiple languages and contexts — built with Laravel 12.

---

## 🚀 Features

- ✅ Store translations for multiple locales (e.g., `en`, `fr`, `es`)
- ✅ Tag translations for context (e.g., `mobile`, `desktop`, `web`)
- ✅ Create, read, update, delete (CRUD) translations
- ✅ Search translations by tags, keys, or content
- ✅ JSON export endpoint for frontend usage (Vue.js compatible)
- ✅ Token-based API authentication (Laravel Sanctum)
- ✅ Command for seeding 100,000+ records to test scalability
- ✅ Performance-tested endpoints (<200ms general, <500ms export)
- ✅ Dockerized for easy development and deployment
- ✅ OpenAPI (Swagger) documentation

---

## 🛠️ Tech Stack

- **Laravel 12**
- **MySQL 8**
- **Redis (optional caching)**
- **Laravel Sanctum** for API authentication
- **PHP 8.2 (FPM)**
- **Nginx (Alpine)**
- **Docker + Docker Compose**

---

## 🧩 System Design Overview

### 📄 Database Schema

- **languages**: stores locales (e.g., `en`, `fr`)
- **tags**: stores context labels
- **translation_keys**: stores unique translation keys
- **translations**: stores localized content by language
- **translation_tag**: many-to-many pivot table

### 🧠 Design Principles

- Follows **SOLID** principles
- Clean architecture separating services, repositories, requests
- **PSR-12** coding standards

---

## 📦 Installation & Setup (Docker)

### 🔧 Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- Git

### 🧱 Steps

```bash
# Clone the repo
git clone https://github.com/arham-natiq25/translation-service
cd translation-service

# Start containers
docker-compose up -d --build

# Enter app container
docker exec -it laravel_app bash

# Generate app key
php artisan key:generate

# Migrate the database
php artisan migrate

# Seed with 100k+ records (optional)
php artisan db:seed

# Run the test suite
php artisan test
```

> App will be available at: [http://localhost:8000](http://localhost:8000)

---

## 🔒 Authentication Flow

- **POST `/api/login`**: Login and receive Sanctum token  
- **Use Bearer token** in headers for protected routes  
- **POST `/api/logout`**: Revoke token to log out  

Once authenticated, all `/api/translations` and `/api/export` endpoints become available.

---

## 📡 API Routes Overview

| Method | Endpoint                  | Description                          | Auth Required |
|--------|---------------------------|--------------------------------------|---------------|
| POST   | `/api/login`              | Login and receive token              | ❌ No         |
| POST   | `/api/logout`             | Logout (invalidate token)           | ✅ Yes        |
| GET    | `/api/translations`       | List all translations                | ✅ Yes        |
| POST   | `/api/translations`       | Create a new translation             | ✅ Yes        |
| GET    | `/api/translations/{id}`  | Show a specific translation          | ✅ Yes        |
| PUT    | `/api/translations/{id}`  | Update translation                   | ✅ Yes        |
| DELETE | `/api/translations/{id}`  | Delete translation                   | ✅ Yes        |
| GET    | `/api/export`             | Export all translations as JSON      | ✅ Yes        |
| GET    | `/api/docs`               | Swagger UI documentation             | ❌ No         |
| GET    | `/api/swagger.json`       | OpenAPI JSON spec                    | ❌ No         |

---

## 🧾 Swagger / OpenAPI Docs

This project includes **interactive API documentation** using Swagger.

- Access Swagger UI: [http://localhost:8000/api/docs](http://localhost:8000/api/docs)
- JSON spec (for Postman import): [http://localhost:8000/api/swagger.json](http://localhost:8000/api/swagger.json)

Backend serves the Swagger files via:

```php
Route::get('/api/docs', [SwaggerController::class, 'index']);
Route::get('/api/swagger.json', [SwaggerController::class, 'json']);
```

---

## 🧪 Testing

### ✅ Run All Tests

```bash
php artisan test
```

### ✅ Test Coverage Includes

- **Unit Tests**: Translation service logic
- **Feature Tests**: API endpoints (CRUD, export)
- **Performance Test**: Export endpoint response time

### 🧪 Performance Testing

Located in `tests/Feature/TranslationPerformanceTest.php`:

```php
$this->assertLessThan(500, $executionTime);
```

Fails if the export endpoint exceeds 500ms on large datasets.

---

## 🧰 Artisan Commands

- `php artisan migrate` – run migrations
- `php artisan db:seed` – seed test data (including 100k+ records)
- `php artisan config:clear` – clear config cache
- `php artisan test` – run all tests
- `php artisan optimize:clear` – clear all compiled files

---

## 🐳 Docker Services Summary

| Service     | Container         | Port           |
|-------------|-------------------|----------------|
| App         | `laravel_app`     | 9000 (internal)|
| Nginx       | `laravel_nginx`   | 8000 (public)  |
| MySQL       | `laravel_mysql`   | 3307 (host)    |

MySQL inside Docker uses `host=mysql`, `port=3306` for app connection.

---

## 🎯 Performance Goals

| Metric                            | Target         |
|----------------------------------|----------------|
| General API response time        | < 200ms        |
| Export endpoint (100k+ records)  | < 500ms        |
| Test coverage                    | > 95%          |

---

## 👨‍💻 Author

**Muhammad Arham**  
Full-Stack Laravel & Vue.js Developer  
🔗 [https://arhamnatiq.com](https://arhamnatiq.com)

---

## 📝 License

This project is for evaluation purposes only as part of the Laravel Senior Developer Test by **DigitalTolk**.
