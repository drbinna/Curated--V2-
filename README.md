# Substack Content Aggregation Hub - Laravel 12 API

A comprehensive REST API for a Substack content aggregation and discovery platform built with Laravel 12.

## Features

- ✅ Laravel Sanctum authentication with personal access tokens
- ✅ Story management (48-hour lifecycle)
- ✅ User profiles (readers & writers)
- ✅ Follow/unfollow system
- ✅ Bookmark/save stories
- ✅ Personalized feed algorithm
- ✅ Full-text search
- ✅ Category-based browsing
- ✅ Real-time notifications (Laravel Reverb)
- ✅ Writer analytics dashboard
- ✅ Image upload (S3-compatible)
- ✅ Rate limiting & security

## Tech Stack

- **Laravel 12.x**
- **PHP 8.3+**
- **PostgreSQL 15+**
- **Redis 7.x**
- **Laravel Sanctum** for authentication
- **Laravel Reverb** for WebSockets
- **Laravel Horizon** for queue monitoring

## Installation

```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start services
php artisan serve
php artisan queue:work
php artisan reverb:start
```

## API Documentation

See [API-README.md](../API-README.md) for complete API documentation.

## License

MIT License





