# URL Shortener Service

A production-grade Laravel API for shortening URLs with concurrent request handling, safety validation, and pre-generated code pools.

## Features

- **URL Shortening**: Convert long URLs into short, unique codes
- **Safe Browsing Integration**: Validate URLs against Google Safe Browsing API to detect malware and phishing threats
- **Race Condition Safe**: Uses `FOR UPDATE SKIP LOCKED` database locking to prevent conflicts
- **Pre-generated Code Pool**: Eliminates real-time code generation overhead
- **Caching Layer**: Redis/file-based caching for fast lookups
- **Deduplication**: Automatic detection of duplicate URLs
- **RESTful API**: Clean JSON-based API endpoints

## Tech Stack

- **Framework**: Laravel 11
- **Language**: PHP 8.3
- **Database**: MySQL
- **Frontend**: Vue 2 + Bootstrap 5
- **Build Tool**: Laravel Mix
- **Containerization**: Docker & Docker Compose

## Quick Start

### Prerequisites
- PHP 8.3+
- Composer
- Node.js & npm
- MySQL 8.0+
- Docker & Docker Compose (optional)

### Local Setup

1. **Clone & Setup Environment**
   ```bash
   cp .env.example .env
   composer update
   npm install
   ```

2. **Configure Database**
   - Update `.env` with your MySQL credentials:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_DATABASE=laravel
     DB_USERNAME=root
     DB_PASSWORD=
     ```

3. **Run Migrations**
   ```bash
   php artisan migrate
   ```

4. **Configure Google Safe Browsing** (optional)
   ```
   SAFE_BRWOSING_API_URL=https://safebrowsing.googleapis.com/v4/threatMatches:find?key=
   SAFE_BRWOSING_API_KEY=your_api_key
   SAFE_BRWOSING_CLIENT_ID=your_client_id
   SAFE_BRWOSING_CLIENT_VERSION=1.0
   SHORT_BASE_URL=http://short.ly/
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```
   Access at http://127.0.0.1:8000/

### Docker Setup

```bash
docker-compose up
```

Application runs on http://localhost:8000

## API Endpoints

### Create Short URL
```http
POST /api/short-url
Content-Type: application/json

{
  "url": "https://example.com/very/long/url"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "success",
  "data": {
    "original_url": "https://example.com/very/long/url",
    "short_url": "http://short.ly/abc123"
  }
}
```

### Retrieve Original URL
```http
GET /api/short-url/{shortCode}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "success",
  "data": {
    "original_url": "https://example.com/very/long/url",
    "short_url": "http://short.ly/abc123"
  }
}
```

## Architecture

### Layered Design
- **Controller Layer** (`ShortUrlController`): HTTP request handling
- **Service Layer** (`UrlShortenerService`): Business logic orchestration
- **Repository Layer** (`EloquentShortUrlRepository`, `CachedShortUrlRepository`): Data access with caching
- **Model Layer**: `ShortUrl`, `CodePool`, `User`

### Key Components

| Component | Purpose |
|-----------|---------|
| `UrlShortenerService` | Orchestrates shortening, validation, and URL lookup |
| `EloquentShortUrlRepository` | Database operations with transaction safety |
| `CachedShortUrlRepository` | Decorator pattern for caching (3600s TTL) |
| `UrlValidationService` | Google Safe Browsing API integration |
| `Base62Service` | URL-friendly code encoding/decoding |
| `SnowflakeGenerator` | Distributed ID generation |

### Data Flow

```
POST /api/short-url
     ↓
ShortUrlController::store()
     ↓
UrlShortenerService::makeShortUrl()
     ├─→ Check URL hash in cache
     ├─→ Validate against Google Safe Browsing
     ├─→ Lock & consume code from code_pool
     └─→ Return cached ShortUrl
     ↓
Response with short_url
```

## Development

### Running Tests
```bash
composer update
php artisan migrate
npm install
```

### Building Assets
```bash
npm run dev          # Development build
npm run watch        # Watch mode for changes
npm run production   # Minified production build
```

### Database Migrations
```bash
php artisan migrate           # Run migrations
php artisan migrate:rollback  # Rollback
php artisan migrate:reset     # Reset all
```

## Configuration

All constants are defined in `config/constants.php`:

| Constant | Default | Purpose |
|----------|---------|---------|
| `CODE_POOL_TARGET_SIZE` | 10,000 | Target pre-generated codes |
| `CODE_POOL_BATCH_SIZE` | 500 | Batch size for generation |
| `SHORT_BASE_URL` | - | Prefix for short codes |

## Error Handling

### Custom Exceptions
- `PoolEmptyException`: No available codes in pool (500)
- `UnsafeUrlException`: URL flagged as malware/phishing (400)
- `BaseException`: Base exception handler

All errors return JSON: `{success: false, message: "error_description"}`

## Project Structure

```
app/
├── Services/           # Business logic
├── Repositories/       # Data access layer
├── Utilities/          # Helper services
├── Models/             # Eloquent models
├── Http/
│   ├── Controllers/    # Request handlers
│   └── Requests/       # Form validation
├── Contracts/          # Interfaces
└── Exceptions/         # Custom exceptions

config/
└── constants.php       # App-wide constants

routes/
└── api.php            # API routes

database/
├── migrations/        # Schema changes
└── seeders/          # Data seeders
```

## Performance Considerations

- **Pre-generated Codes**: Avoids real-time generation bottleneck
- **Caching**: 1-hour TTL reduces database queries
- **Database Locking**: `FOR UPDATE SKIP LOCKED` prevents race conditions
- **Deduplication**: URL hash lookup prevents duplicate shortening

## Contributing

See `.github/copilot-instructions.md` for AI coding agent guidelines.

## License

MIT
