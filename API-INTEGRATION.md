## Curated API Integration Guide (Figma Make)

Base URL: `https://api.curated.forum`

Authentication: Bearer tokens via Laravel Sanctum
- Header: `Authorization: Bearer {token}`
- Content-Type: `application/json`

---

### Health

- GET /api/health
  - Response 200:
```json
{"status":"ok"}
```

---

### Authentication

- POST /api/auth/register
  - Body:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "securePass123",
  "password_confirmation": "securePass123",
  "user_type": "reader",
  "device_name": "figma_make"
}
```
  - Response 201:
```json
{
  "success": true,
  "data": {
    "user": {
      "id": "uuid",
      "name": "John Doe",
      "email": "john@example.com",
      "user_type": "reader"
    },
    "token": "1|sanctum_token_here",
    "token_type": "Bearer"
  }
}
```

- POST /api/auth/login
  - Body:
```json
{
  "email": "john@example.com",
  "password": "securePass123",
  "device_name": "figma_make"
}
```
  - Response 200: same shape as register

- GET /api/auth/me
  - Headers: Authorization
  - Response 200:
```json
{
  "success": true,
  "data": {
    "id": "uuid",
    "name": "John Doe",
    "email": "john@example.com",
    "username": "johndoe",
    "user_type": "reader",
    "bio": "Reader bio",
    "avatar_url": null,
    "followers_count": 0,
    "following_count": 0,
    "stories_count": 0
  }
}
```

- POST /api/auth/logout
- POST /api/auth/logout-all
- GET /api/auth/tokens
- DELETE /api/auth/tokens/{tokenId}
- PUT /api/auth/profile
  - Body (any subset):
```json
{
  "name": "New Name",
  "username": "newuser",
  "bio": "About me",
  "avatar_url": "https://..."
}
```

- GET /api/auth/substack
- GET /api/auth/substack/callback
- POST /api/auth/substack/disconnect

---

### Users

- GET /api/users/{userId}
  - Response 200:
```json
{
  "success": true,
  "data": {
    "id": "uuid",
    "name": "Jane Writer",
    "username": "janewriter",
    "bio": "...",
    "avatar_url": null,
    "followers_count": 23,
    "following_count": 10,
    "stories_count": 3,
    "is_following": false,
    "stories": [],
    "publications": []
  }
}
```

- POST /api/users/{userId}/follow
- DELETE /api/users/{userId}/follow
- GET /api/users/{userId}/followers
- GET /api/users/{userId}/following
- GET /api/users/{userId}/stories

---

### Stories

- GET /api/stories
  - Query: `status`, `user_id`, `page`
  - Response 200 (paginated):
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": "uuid",
        "title": "...",
        "excerpt": "...",
        "image_url": null,
        "substack_post_url": "https://...",
        "published_at": "2025-10-25T10:00:00Z",
        "expires_at": "2025-10-27T10:00:00Z",
        "status": "active",
        "view_count": 12,
        "click_count": 3,
        "save_count": 1,
        "share_count": 0,
        "user": { "id": "uuid", "name": "...", "username": "..." },
        "publication": { "id": "uuid", "name": "..." },
        "categories": [ {"id":"uuid","name":"Technology"} ]
      }
    ],
    "last_page": 1,
    "per_page": 20,
    "total": 1
  }
}
```

- GET /api/stories/bar
- GET /api/stories/trending

- POST /api/stories
  - Body:
```json
{
  "publication_id": "uuid",
  "title": "My Latest Newsletter Post",
  "excerpt": "Preview...",
  "image_url": "https://...",
  "substack_post_url": "https://newsletter.substack.com/p/my-post",
  "category_ids": ["uuid1", "uuid2"],
  "publish_now": true
}
```
  - Response 201:
```json
{
  "success": true,
  "data": {
    "id": "uuid",
    "title": "My Latest Newsletter Post",
    "status": "active",
    "published_at": "2025-10-25T12:00:00Z",
    "expires_at": "2025-10-27T12:00:00Z"
  },
  "message": "Story created successfully"
}
```

- GET /api/stories/{storyId}
- PUT /api/stories/{storyId}
  - Body (any subset): `title`, `excerpt`, `image_url`
- DELETE /api/stories/{storyId}
- POST /api/stories/{storyId}/view
- POST /api/stories/{storyId}/click

---

### Feed

- GET /api/feed
  - Query: `page`, `per_page`, `filter` (all|following), `category` (slug)
  - Response 200 (paginated same structure as stories), with extra flags:
    - `is_bookmarked`: boolean
    - `is_viewed`: boolean

---

### Bookmarks

- GET /api/bookmarks
- POST /api/bookmarks
  - Body: `{ "story_id": "uuid" }`
- DELETE /api/bookmarks/{storyId}

---

### Categories

- GET /api/categories
- GET /api/categories/{categoryId}
- GET /api/categories/{categoryId}/stories
- POST /api/categories/{categoryId}/follow
- DELETE /api/categories/{categoryId}/follow

---

### Publications

- GET /api/publications
- GET /api/publications/{publicationId}
- POST /api/publications/{publicationId}/sync

---

### Search

- GET /api/search?q=term
  - Response 200:
```json
{
  "success": true,
  "data": {
    "stories": {"data": []},
    "users": {"data": []},
    "publications": {"data": []}
  }
}
```

- GET /api/search/stories?q=term
- GET /api/search/users?q=term
- GET /api/search/publications?q=term

---

### Analytics

- GET /api/analytics/stories/{storyId}
  - Response 200:
```json
{
  "success": true,
  "data": {
    "story_id": "uuid",
    "title": "...",
    "metrics": {
      "impressions": 0,
      "views": 2500,
      "clicks": 450,
      "saves": 120,
      "shares": 35,
      "avg_view_duration": 8.5,
      "completion_rate": 65.5,
      "click_through_rate": 18.0
    }
  }
}
```

- GET /api/analytics/dashboard
- GET /api/analytics/audience

---

### Notifications

- GET /api/notifications
- PUT /api/notifications/{notificationId}/read
- PUT /api/notifications/read-all
- DELETE /api/notifications/{notificationId}

---

### Upload (Local Storage)

- POST /api/upload/image (multipart/form-data)
  - Headers: `Authorization: Bearer {token}`
  - Fields:
    - `image` (file) — required; max 5MB; types: jpeg, jpg, png, gif, webp
    - `type` (string) — required; one of: `story`, `avatar`, `publication`
  - Validation:
    - MIME: image/jpeg, image/png, image/gif, image/webp
    - Extension whitelist: jpeg, jpg, png, gif, webp
  - Response 200:
```json
{
  "success": true,
  "data": {
    "url": "https://api.curated.forum/storage/uploads/stories/abc123.jpg",
    "path": "uploads/stories/abc123.jpg"
  }
}
```
  - Notes:
    - Files are stored on the local `public` disk under `storage/app/public/uploads/...`
    - Ensure `php artisan storage:link` is run once in the environment

- DELETE /api/upload/image
  - Body:
```json
{
  "path": "uploads/stories/abc123.jpg"
}
```
  - Behavior: Only paths under `uploads/stories`, `uploads/avatars`, or `uploads/publications` are deletable.

---

### Errors

- Standard error format:
```json
{
  "success": false,
  "error": {
    "message": "The given data was invalid.",
    "code": "VALIDATION_ERROR",
    "details": {
      "image": ["The image must be a file of type: jpeg, jpg, png, gif, webp."]
    }
  }
}
```

Common codes: AUTH_FAILED, TOKEN_INVALID, VALIDATION_ERROR, RESOURCE_NOT_FOUND, RATE_LIMIT_EXCEEDED

---

### Rate Limits

- Authenticated: 60/minute
- Unauthenticated: 20/minute
- Special: login 10/min, register 5/min, upload 10/min

Headers: `X-RateLimit-Limit`, `X-RateLimit-Remaining`, `Retry-After`

---

### How to use in Figma Make

- Set HTTP action URL to `https://api.curated.forum/{endpoint}`
- Add header `Authorization: Bearer {{token}}`
- Set `Content-Type: application/json` for JSON requests
- For file upload, use multipart/form-data

---

### Notes

- All IDs are UUID strings
- Times are ISO8601 UTC
- Pagination uses Laravel paginator structure under `data`
