# Translation Management Service

A high-performance API-driven service for managing translations across multiple languages and contexts.

## Features

- Store translations for multiple locales (e.g., en, fr, es)
- Tag translations for context (e.g., mobile, desktop, web)
- CRUD operations for translations
- Search translations by tags, keys, or content
- JSON export endpoint for frontend applications
- High performance with response times < 200ms
- Scalable to handle 100k+ records

## Technical Stack

- Laravel 10
- MySQL
- Redis for caching
- Docker for containerization
- Laravel Sanctum for authentication

## Design Choices

### Database Schema

The database schema is designed for optimal performance and scalability:

- **Languages**: Stores available languages
- **Tags**: Stores context tags
- **TranslationKeys**: Stores unique translation keys
- **Translations**: Stores the actual translation content
- **TranslationTag**: Many-to-many relationship between keys and tags

This normalized structure allows for efficient querying and reduces data redundancy.

### Performance Optimizations

- **Caching**: Redis is used to cache translations by language and tags
- **Indexing**: Strategic indexes on frequently queried columns
- **Query Optimization**: Efficient SQL queries with proper joins and where clauses
- **CDN Support**: Cache headers for the JSON export endpoint

### Security

- Token-based authentication using Laravel Sanctum
- Input validation using Form Request classes
- CSRF protection for web routes

## Setup Instructions

### Prerequisites

- Docker and Docker Compose
- Git

### Installation

1. Clone the repository:
