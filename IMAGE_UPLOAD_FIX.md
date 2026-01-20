# Image Upload Fix Guide

**Date:** January 7, 2026  
**Issue:** Curator media picker upload failing

---

## Problem

**Error:** `The data.files_to_add.5ec7c7ec-4d1e-46d9-aed1-f573b55cd569 failed to upload`

**Cause:** Missing `media` directory in storage

---

## Solution Applied

### **1. Created Media Directory**

```bash
sudo mkdir -p storage/app/public/media
sudo chown -R www-data:www-data storage/app/public/media
sudo chmod -R 775 storage/app/public/media
```

### **2. Improved Curator Picker UI**

Updated `PageResource.php` to make the image picker more user-friendly:

```php
\Awcodes\Curator\Components\Forms\CuratorPicker::make('section_data.hero.image')
    ->label('Background Image')
    ->buttonLabel('Choose Image')  // Clear button text
    ->size('lg')                    // Larger picker
    ->lazyLoad()                    // Better performance
```

### **3. Cleared Caches**

```bash
php artisan config:cache
php artisan optimize:clear
```

---

## How to Upload Images Now

1. **Edit a page** in `/admin/pages`
2. **Enable Hero Banner** toggle (right sidebar)
3. **Scroll to Hero Banner section** (center column)
4. **Click "Choose Image"** button
5. **Upload or select image** from media library
6. **Click "Insert"**
7. **Save page**

---

## If Upload Still Fails

### **Check Permissions:**

```bash
# Check current permissions
ls -la storage/app/public/

# Fix if needed
sudo chown -R www-data:www-data storage/app/public/
sudo chmod -R 775 storage/app/public/
```

### **Check Storage Link:**

```bash
# Verify symbolic link exists
ls -la public/storage

# If missing, create it
php artisan storage:link
```

### **Check PHP Upload Limits:**

```bash
# Check current limits
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Should be at least:
# upload_max_filesize = 10M
# post_max_size = 10M
```

### **Check Disk Space:**

```bash
df -h
```

---

## Configuration

### **Curator Config** (`config/curator.php`):

```php
'disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),
'directory' => 'media',
'max_size' => 5000,  // 5MB max
'accepted_file_types' => [
    'image/jpeg',
    'image/png',
    'image/webp',
    'image/svg+xml',
],
```

### **Filesystem Config** (`config/filesystems.php`):

```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => '/storage',
    'visibility' => 'public',
],
```

---

## UI Improvements Made

### **Before:**
- Small picker button
- No clear label
- Generic appearance

### **After:**
- ✅ Larger picker (`size='lg'`)
- ✅ Clear button label ("Choose Image")
- ✅ Lazy loading for better performance
- ✅ Better visual feedback

---

## Testing

### **Test Upload:**

1. Go to `/admin/pages/home/edit`
2. Enable "Hero Banner" (right sidebar)
3. Click "Choose Image" in Hero Banner section
4. Try uploading a test image
5. Should see upload progress
6. Image should appear in picker
7. Click "Insert" to select it
8. Save page

### **Verify Image on Frontend:**

1. Visit homepage: `http://laracommerce.com/`
2. Hero banner should show your uploaded image
3. Check browser console for any errors

---

## Common Issues

### **Issue: "Failed to upload"**

**Solutions:**
- Check directory permissions (775)
- Check ownership (www-data:www-data)
- Check disk space
- Check PHP upload limits

### **Issue: "Image not showing on frontend"**

**Solutions:**
- Check storage link exists: `ls -la public/storage`
- Clear browser cache
- Check image URL in page source
- Verify image exists: `ls storage/app/public/media/`

### **Issue: "Picker UI looks broken"**

**Solutions:**
- Clear Filament cache: `php artisan filament:cache-components`
- Clear browser cache (Ctrl+Shift+R)
- Check for JavaScript errors in console

---

## Summary

**Fixed:**
- ✅ Created missing media directory
- ✅ Set correct permissions
- ✅ Improved Curator picker UI
- ✅ Added better button labels
- ✅ Cleared caches

**Image uploads should now work!**

Try uploading an image for the hero banner background.
