# Laravel 12 API Project Structure

## Overview
Complete Laravel 12 API for Substack Content Aggregation Hub with 60+ endpoints.

## Project Structure

```
substack-hub-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Authentication endpoints
│   │   │   ├── StoryController.php         # Story CRUD operations
│   │   │   ├── UserController.php          # User management
│   │   │   ├── FeedController.php          # Personalized feed
│   │   │   ├── BookmarkController.php       # Bookmark management
│   │   │   ├── CategoryController.php      # Category browsing
│   │   │   ├── PublicationController.php    # Publication management
│   │   │   ├── SearchController.php         # Search functionality
│   │   │   ├── AnalyticsController.php      # Analytics dashboard
│   │   │   ├── NotificationController.php    # Notifications
│   │   │   └── UploadController.php          # Image uploads
│   │   ├── Middleware/
│   │   │   ├── Authenticate.php
│   │   │   ├── EncryptCookies.php
│   │   │   ├── RedirectIfAuthenticated.php
│   │   │   ├── ValidateSignature.php
│   │   │   └── VerifyCsrfToken.php
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── User.php                        # Users (readers & writers)
│   │   ├── Story.php                       # Stories with 48h lifecycle
│   │   ├── Publication.php                # Publications
│   │   ├── Category.php                   # Categories
│   │   ├── Follow.php                     # User follows
│   │   ├── Bookmark.php                   # Bookmarks
│   │   ├── Notification.php               # Notifications
│   │   ├── StoryView.php                  # View tracking
│   │   └── StoryClick.php                 # Click tracking
│   ├── Policies/
│   │   ├── StoryPolicy.php
│   │   └── NotificationPolicy.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   ├── Services/
│   └── Repositories/
├── bootstrap/
│   └── app.php                            # Laravel 12 bootstrap
├── config/
│   ├── app.php                            # App configuration
│   ├── database.php                       # Database config
│   ├── sanctum.php                       # Sanctum auth config
│   ├── cors.php                          # CORS configuration
│   ├── filesystems.php                   # File storage
│   ├── session.php                       # Session config
│   ├── cache.php                         # Cache config
│   ├── queue.php                         # Queue config
│   ├── broadcasting.php                 # WebSocket config
│   └── logging.php                      # Logging config
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_personal_access_tokens_table.php
│   │   ├── 2024_01_01_000003_create_publications_table.php
│   │   ├── 2024_01_01_000004_create_categories_table.php
│   │   ├── 2024_01_01_000005_create_stories_table.php
│   │   ├── 2024_01_01_000006_create_story_categories_table.php
│   │   ├── 2024_01_01_000007_create_follows_table.php
│   │   ├── 2024_01_01_000008_create_bookmarks_table.php
│   │   ├── 2024_01_01_000009_create_story_views_table.php
│   │   ├── 2024_01_01_000010_create_story_clicks_table.php
│   │   ├── 2024_01_01_000011_create_notifications_table.php
│   │   ├── 2024_01_01_000012_create_publication_follows_table.php
│   │   ├── 2024_01_01_000013_create_category_follows_table.php
│   │   ├── 2024_01_01_000014_create_sessions_table.php
│   │   ├── 2024_01_01_000015_create_cache_table.php
│   │   └── 2024_01_01_000016_create_jobs_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── CategorySeeder.php
│       └── UserSeeder.php
├── public/
│   ├── index.php                         # Entry point
│   └── .htaccess
├── routes/
│   ├── api.php                           # API routes
│   ├── web.php                          # Web routes
│   └── console.php                      # Artisan commands
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
├── tests/
│   ├── Feature/
│   ├── Unit/
│   ├── TestCase.php
│   └── CreatesApplication.php
├── artisan                               # CLI tool
├── composer.json                        # PHP dependencies
├── package.json                         # NPM dependencies
├── vite.config.js                       # Vite config
├── tailwind.config.js                   # Tailwind config
├── phpunit.xml                          # PHPUnit config
└── README.md                            # Project documentation
```

## Key Features Implemented

### ✅ Authentication & Authorization
- Laravel Sanctum for API authentication
- Personal access tokens
- Token management (view, revoke)
- OAuth integration with Substack

### ✅ Core Functionality
- User registration & login
- Story management (48-hour lifecycle)
- Publication management
- Category browsing
- Follow/unfollow system
- Bookmark functionality
- Personalized feed
- Full-text search
- Analytics dashboard

### ✅ Data Models (All with UUID)
1. **Users** - Readers & Writers with profiles
2. **Stories** - Content with expiration tracking
3. **Publications** - Newsletter sources
4. **Categories** - Content categorization
5. **Follows** - User relationships
6. **Bookmarks** - Saved stories
7. **Notifications** - User notifications
8. **StoryViews** - View tracking
9. **StoryClicks** - Click tracking

### ✅ Controllers (60+ Endpoints)
- **AuthController** (11 endpoints) - Registration, login, logout, token management
- **StoryController** (9 endpoints) - Story CRUD, views, clicks, trending
- **FeedController** - Personalized feed algorithm
- **UserController** (6 endpoints) - User profiles, follow/unfollow
- **BookmarkController** (3 endpoints) - Bookmark management
- **CategoryController** (5 endpoints) - Category browsing
- **PublicationController** (2 endpoints) - Publication management
- **SearchController** (4 endpoints) - Search functionality
- **AnalyticsController** (3 endpoints) - Analytics dashboard
- **NotificationController** (4 endpoints) - Notifications
- **UploadController** (2 endpoints) - Image uploads

### ✅ Database Schema
- PostgreSQL with UUID primary keys
- 13 migrations for core tables
- Proper indexing for performance
- Foreign key constraints
- Cascade deletes

### ✅ Security Features
- CSRF protection
- Rate limiting (60/min authenticated, 20/min unauthenticated)
- Token abilities/scopes
- Policy-based authorization
- CORS configuration
- Input validation

## Next Steps

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure Database**
   Edit `.env` with PostgreSQL credentials

4. **Run Migrations**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start Services**
   ```bash
   php artisan serve
   php artisan queue:work
   php artisan reverb:start
   ```

## API Documentation

Full API documentation is available in `API-README.md` with detailed endpoint specifications, request/response examples, error handling, rate limiting, and WebSocket events.








