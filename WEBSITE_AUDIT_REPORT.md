# Website Component Audit Report

**Date:** January 7, 2026  
**Status:** 🔴 Critical Issues Found

---

## Issues Discovered

### 🔴 **ISSUE #1: Logo Not Showing on Frontend**

**Status:** FOUND ROOT CAUSE  
**Severity:** High  
**Location:** Header & Footer

**Database State:**
```json
{
  "site_logo_text": "Nisargalahari",
  "site_logo_image": "38"  // Media ID
}
```

**Media Check:**
```json
{
  "exists": true,
  "url": "/storage/logo/01ke82m6crv96b9ag5q3yw1eb8.png",
  "path": "logo/01ke82m6crv96b9ag5q3yw1eb8.png"
}
```

**Root Cause:**
The logo IS in the database and Media ID 38 exists. The issue is in `header.blade.php` line 44:
```php
<img src="{{ $siteLogoImage }}" alt="{{ $siteLogoText }}" class="h-8 w-auto mr-2">
```

The `$siteLogoImage` variable uses `SiteSetting::getMediaUrl('site_logo_image')` which should resolve to the URL, but there might be a caching issue or the MediaHelper is not resolving correctly.

**Fix Required:**
1. Clear view cache
2. Verify MediaHelper::resolveUrl() is working
3. Check if storage link exists

---

### 🔴 **ISSUE #2: Hero Banner Showing Despite Deletion**

**Status:** FOUND ROOT CAUSE  
**Severity:** Critical  
**Location:** Homepage

**Database State:**
```json
{
  "homepage_page": {
    "id": 5,
    "title": "home",
    "blocks": [
      {
        "type": "product_grid",
        "data": {
          "heading": "Some our products",
          "type": "featured",
          "count": "1"
        }
      }
    ]
  },
  "site_settings": {
    "hero_title": "Welcome to Nisargalahari",
    "hero_background_image": null
  }
}
```

**Root Cause:**
The homepage is NOT using the Page Builder system! It's using the OLD `home.blade.php` view which pulls from Site Settings, not from the Page blocks.

**Current Flow:**
1. User visits `/` 
2. `PageController::homepage()` checks for homepage page
3. Page exists (id=5) with blocks
4. Should route to `pages/builder.blade.php`
5. BUT it's routing to `home.blade.php` instead!

**Problem in PageController:**
```php
// PageController.php line 38-40
if ($page && !empty($page->blocks)) {
    return view('pages.builder', compact('page'));
}
```

This should work, but then line 44:
```php
return $this->show($page->slug);
```

Which calls `show()` method that might be using wrong template.

**ACTUAL PROBLEM:**
The route `/` is NOT going through PageController at all! Check routes/web.php line 7:
```php
Route::get('/', [App\Http\Controllers\PageController::class, 'homepage'])->name('home');
```

But there might be a static `home.blade.php` being rendered instead of the dynamic page!

---

### 🔴 **ISSUE #3: Dual Homepage System**

**Status:** Architecture Problem  
**Severity:** Critical

**Two Systems Coexisting:**

1. **Old System** (Currently Active):
   - View: `resources/views/home.blade.php`
   - Data: Site Settings (`hero_title`, `hero_background_image`, etc.)
   - Hardcoded layout

2. **New System** (Should Be Active):
   - View: `resources/views/pages/builder.blade.php`
   - Data: Page blocks from database
   - Dynamic builder

**The Problem:**
The `PageController::homepage()` is returning the wrong view. Even though the page has blocks, it's not using the builder view.

---

## Component Audit

### ✅ **Header Component**
- **File:** `resources/views/layouts/partials/header.blade.php`
- **Logo Logic:** Lines 43-48
- **Status:** Code is correct, but logo not displaying
- **Dependencies:** SiteSetting model, MediaHelper

### ✅ **Footer Component**
- **File:** `resources/views/layouts/partials/footer.blade.php`
- **Logo Logic:** Similar to header
- **Status:** Same issue as header

### ⚠️ **Homepage**
- **File:** `resources/views/home.blade.php`
- **Status:** SHOULD NOT BE USED - conflicts with Page Builder
- **Issue:** Hardcoded hero section using Site Settings

### ✅ **Page Builder**
- **File:** `resources/views/pages/builder.blade.php`
- **Status:** Correct implementation
- **Issue:** Not being used for homepage

### ✅ **Hero Builder Component**
- **File:** `resources/views/components/blocks/hero-builder.blade.php`
- **Status:** Correct implementation
- **Issue:** Not being called because homepage uses old view

---

## Database Audit

### Site Settings Table:
```
✅ site_logo_text: "Nisargalahari"
✅ site_logo_image: "38" (Media ID exists)
⚠️ hero_title: "Welcome to Nisargalahari" (OLD SYSTEM)
⚠️ hero_background_image: null (OLD SYSTEM)
```

### Pages Table:
```
✅ Homepage page exists (id=5)
✅ Has blocks array with product_grid
✅ is_published: true
✅ is_homepage: true
❌ NO hero block in blocks array
```

### Media Table:
```
✅ ID 38 exists
✅ Path: logo/01ke82m6crv96b9ag5q3yw1eb8.png
✅ URL: /storage/logo/01ke82m6crv96b9ag5q3yw1eb8.png
```

---

## Fixes Required

### Fix #1: Logo Display
1. Clear all caches
2. Verify storage link exists
3. Test MediaHelper::resolveUrl(38)
4. Add fallback image if needed

### Fix #2: Homepage Routing
1. Update PageController::homepage() to ALWAYS use builder for pages with blocks
2. Deprecate home.blade.php
3. Remove hero settings from Site Settings (or mark as deprecated)

### Fix #3: Hero Banner
1. User deleted hero from Filament (correct)
2. But old home.blade.php still shows hero from Site Settings
3. Solution: Use Page Builder view instead

---

## Recommended Actions

### Immediate (Critical):
1. ✅ Fix PageController::homepage() to use builder view
2. ✅ Rename home.blade.php to home.blade.php.old (backup)
3. ✅ Clear all caches
4. ✅ Test logo display
5. ✅ Test homepage without hero

### Short-term:
1. Migrate hero settings to Page blocks
2. Remove old hero settings from Site Settings
3. Update documentation

### Long-term:
1. Remove home.blade.php entirely
2. Clean up unused Site Settings
3. Standardize all pages to use Page Builder

---

## Testing Checklist

- [ ] Visit homepage - should show NO hero banner
- [ ] Visit homepage - should show product grid only
- [ ] Check header - logo should display
- [ ] Check footer - logo should display
- [ ] Edit homepage in Filament - should work
- [ ] Add hero block - should display on frontend
- [ ] Remove hero block - should disappear from frontend

---

**Next Steps:** Apply fixes in order of priority
