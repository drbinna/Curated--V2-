# Image Storage Guide

## How Images Are Saved

1. **Upload Process:**
   - Images are uploaded via `POST /api/upload/image`
   - Files are saved to `storage/app/public/uploads/{type}/filename.ext`
   - The **URL** (not the file) is saved in the database as `image_url` field

2. **Database Storage:**
   - The `stories` table has an `image_url` column (already in fillable)
   - This stores the full URL to the image, e.g., `/storage/uploads/stories/uuid.png`
   - The URL is saved when creating/updating a story

3. **File Persistence:**
   - Uploaded images are **NOT** deleted during deployments
   - Rsync excludes `storage/app/public/uploads` from deletion
   - Uploaded files persist across deployments

## Important Notes

### ✅ What's Saved in Database
- **`image_url`** field in `stories` table stores the URL/path to the image
- This is already in the `$fillable` array in Story model

### ✅ Files Are Preserved
- Deployment workflow excludes uploads directory from rsync deletion
- Directories are created if they don't exist
- Images persist even after deployments

### ✅ Creating Stories with Images
When you create a story:
1. Upload image first: `POST /api/upload/image` → get `url` in response
2. Use that `url` in story creation: `POST /api/stories` with `image_url` field

**Example:**
```json
// Step 1: Upload
POST /api/upload/image
{
  "image": <file>,
  "type": "story"
}
// Response: { "url": "/storage/uploads/stories/abc123.png" }

// Step 2: Create story with image URL
POST /api/stories
{
  "title": "...",
  "excerpt": "...",
  "image_url": "/storage/uploads/stories/abc123.png",
  "substack_post_url": "..."
}
```

## Troubleshooting

If images are deleted:
1. Check if `storage/app/public/uploads/` directories exist
2. Verify rsync exclusions in deployment workflow
3. Ensure `php artisan storage:link` has been run
4. Check file permissions: `chmod -R 775 storage/app/public/uploads`

