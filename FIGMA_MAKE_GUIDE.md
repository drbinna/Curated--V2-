# Figma Make: Get Other Users' Posts

**Base URL:** `https://api.curated.forum`

---

## Get Other Users' Posts

**Module:** HTTP > Make a Request  
**Method:** GET  
**URL:** `https://api.curated.forum/api/stories/others`

**Headers:**
```
Authorization: Bearer {{auth_token}}
```

**Query Parameters (Optional):**
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 20)
- `category` - Filter by category slug (e.g., "technology")

**Example URL:**
```
https://api.curated.forum/api/stories/others?page=1&per_page=20&category=technology
```

---

## Response (200 OK)

```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": "770e8400-e29b-41d4-a716-446655440005",
        "title": "Other User's Story Title",
        "excerpt": "Story preview text...",
        "image_url": "https://api.curated.forum/public/storage/uploads/stories/abc123.png",
        "substack_post_url": "https://user.substack.com/p/story",
        "published_at": "2025-10-29T10:00:00.000000Z",
        "expires_at": "2025-10-31T10:00:00.000000Z",
        "status": "active",
        "view_count": 456,
        "click_count": 89,
        "save_count": 23,
        "share_count": 5,
        "user": {
          "id": "110e8400-e29b-41d4-a716-446655440006",
          "name": "Jane Writer",
          "username": "janewriter",
          "avatar_url": null
        },
        "categories": [
          {
            "id": "550e8400-e29b-41d4-a716-446655440001",
            "name": "Technology",
            "slug": "technology"
          }
        ]
      }
    ],
    "last_page": 10,
    "per_page": 20,
    "total": 200
  }
}
```

---

## Notes

- **Requires Authentication:** Must include Bearer token in Authorization header
- **Excludes Your Posts:** Automatically filters out logged-in user's stories
- **Only Active Stories:** Returns only active, non-expired stories
- **Pagination:** Use `page` and `per_page` to navigate results
