# 🌐 Translation Management Service

A high-performance, scalable API-driven service for managing translations across multiple languages and contexts — built with Laravel.

---

##  Features

- ✅ Store translations for multiple locales (e.g., `en`, `fr`, `es`)
- ✅ Tag translations for context (e.g., `mobile`, `desktop`, `web`)
- ✅ Create, read, update, delete (CRUD) translations
- ✅ Search translations by tags, keys, or content
- ✅ JSON export endpoint for frontend usage (Vue.js compatible)
- ✅ Token-based API authentication (Laravel Sanctum)
- ✅ Command for seeding 100,000+ records to test scalability
- ✅ Performance-tested endpoints (<200ms general, <500ms export)
- ✅ Dockerized for easy development and deployment

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

### ⚡ Performance Optimizations

- Indexed columns on keys, language IDs, and tag relations
- Eager loading of relationships
- Response caching with Redis
- CDN support with cache headers
- JSON export endpoint optimized for <500ms with 100k+ records

---

## 🔒 Security

- API secured with **Laravel Sanctum tokens**
- All inputs validated using **FormRequest** classes
- Web routes protected with **CSRF tokens**

---

## 📦 Installation & Setup (Docker)

### 🔧 Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- Git

### 🔥 Steps

```bash
# Clone the repo
git clone https://github.com/arham-natiq25/translation-service
cd translation-service

# Start services
docker-compose up -d --build

# Enter app container
docker exec -it laravel_app bash

# Set Laravel key
php artisan key:generate

# Migrate database
php artisan migrate

# Optionally seed with dummy data
php artisan db:seed

# Run tests
php artisan test
