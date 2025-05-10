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
