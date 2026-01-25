# Copilot Instructions for URL Shortener

## Project Overview
Laravel-based URL shortening service with pre-generated code pools, Snowflake ID generation, and Google Safe Browsing API integration for malware/phishing detection.

## Architecture

### Core Components
- **Service Layer**: `UrlShortenerService` implements `UrlShortenerContract` - orchestrates shortening logic
- **Repository Layer**: `EloquentShortUrlRepository` + `CachedShortUrlRepository` - data access with caching
- **Models**: `ShortUrl` (main), `CodePool` (pre-generated codes), `User`
- **Utilities**: `Base62Service` (encoding), `SnowflakeGenerator` (distributed IDs), `UrlValidationService` (safety checks)

### Data Flow
1. User sends URL to `ShortUrlController::store()`
2. `UrlShortenerService` validates URL hash existence
3. `UrlValidationService` checks against Google Safe Browsing API
4. Repository atomically consumes pre-generated code from pool and creates `ShortUrl`
5. Cached repository returns full URL with short code

### Key Patterns
- **Dependency Injection**: All services bound in `UrlShortenerServiceProvider`
- **Transaction Safety**: `EloquentShortUrlRepository::create()` uses `FOR UPDATE SKIP LOCKED` to prevent race conditions
- **Pre-generated Pool**: Codes pre-generated and stored in `code_pool` table to eliminate real-time generation overhead
- **Lazy Loading**: `shortCode` is an Eloquent Attribute that appends `SHORT_BASE_URL` config

## Development Workflows

### Setup
```bash
cp .env.example .env
composer update
npm install
php artisan migrate
```

### Running
- **Local**: `php artisan serve` (http://127.0.0.1:8000/)
- **Docker**: `docker-compose up` (port 8000)

### Testing
```bash
php artisan test                    # Run all tests
vendor/bin/phpunit --filter Test    # Run specific tests
```

### Build Assets
```bash
npm run dev          # Development build
npm run watch        # Watch mode
npm run production   # Production build
```

## Configuration & Constants

All app constants via `config/constants.php`:
- `SAFE_BRWOSING_API_*`: Google API credentials (env vars)
- `SHORT_BASE_URL`: Prefix for short codes (e.g., "http://short.ly/")
- `CODE_POOL_TARGET_SIZE`: Target pool size (default: 10,000)
- `CODE_POOL_BATCH_SIZE`: Generation batch size (default: 500)

Response structure: `{success: bool, message: string, data: object}`

## Important Files & Patterns

### Services
- [UrlShortenerService.php](app/Services/UrlShortenerService.php): Core business logic
- [UrlValidationService.php](app/Utilities/UrlValidationService.php): Google Safe Browsing integration

### Repositories
- [EloquentShortUrlRepository.php](app/Repositories/EloquentShortUrlRepository.php): Transaction handling
- [CachedShortUrlRepository.php](app/Repositories/CachedShortUrlRepository.php): Decorator pattern for caching

### Routes
- [routes/api.php](routes/api.php): API resource routes via `Route::resource('short-url', ShortUrlController::class)`

## Common Tasks

**Add URL shortening logic**: Modify `UrlShortenerService::makeShortUrl()`

**Change code generation strategy**: Update `EloquentShortUrlRepository::create()` or create `EloquentCodePoolRepository`

**Add validation rules**: Update [Http/Requests/UrlShortenerRequest.php](app/Http/Requests/UrlShortenerRequest.php)

**Regenerate code pool**: Create artisan command calling `EloquentCodePoolRepository`

**Cache invalidation**: Check `CachedShortUrlRepository` decorator pattern
