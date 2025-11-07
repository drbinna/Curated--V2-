# Storage Setup Guide

## Issue: Images Not Accessible in Browser

If images are saved but not viewable in the browser, you need to create a storage symlink.

## Solution

Run this command once on your server:

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`, making files accessible via the web.

## Verify It Works

After running the command, check:
1. The symlink exists: `ls -la public/storage` (should show a link)
2. Files are accessible: Visit `http://your-domain/storage/uploads/stories/filename.png`

## File Structure

```
storage/
  app/
    public/
      uploads/
        stories/
        avatars/
        publications/

public/
  storage/  ← Symlink created by `php artisan storage:link`
```

## URL Format

Images will be accessible at:
- `http://localhost:8000/storage/uploads/stories/filename.png`
- `https://api.curated.forum/storage/uploads/stories/filename.png`

## Note for Production

Make sure to run `php artisan storage:link` on your production server as well!


