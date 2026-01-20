# Frontend Color Audit - LaraCommerce

## Issue Found: Footer Colors Not Changing

**Problem**: Footer uses `hover:text-primary-500` classes but CDN Tailwind doesn't recognize custom `primary-*` color names.

**Solution**: Need to use inline styles or CSS variables for dynamic colors with CDN Tailwind.

## Files Requiring Updates

### High Priority (Most Visible)
1. ✅ Homepage (`templates/homepage.blade.php`) - DONE
2. ✅ Header (`layouts/partials/header.blade.php`) - DONE  
3. ❌ Footer (`layouts/partials/footer.blade.php`) - NEEDS FIX
4. ✅ Featured Products (`livewire/featured-products.blade.php`) - DONE
5. ❌ Product Detail (`livewire/product-detail.blade.php`) - 20 hardcoded colors
6. ❌ Shopping Cart (`livewire/shopping-cart.blade.php`) - 23 hardcoded colors
7. ❌ Product Listing (`livewire/product-listing.blade.php`) - 5 hardcoded colors

### Medium Priority
8. ❌ Auth Pages (login, register, etc.) - Multiple hardcoded blues
9. ❌ Account Dashboard - 6 hardcoded colors
10. ❌ Checkout - Hardcoded colors
11. ❌ Category Pages - Hardcoded colors
12. ❌ Contact Template - 4 hardcoded colors
13. ❌ About Template - 2 hardcoded colors

### Low Priority (Admin/Internal)
14. ❌ Error Pages (404) - 7 hardcoded colors
15. ❌ Block Components - Various hardcoded colors

## Total Files with Hardcoded Colors: 33 files, 174+ instances

## Strategy

Since we're using CDN Tailwind, custom color classes like `bg-primary-600` won't work.

### Two Options:

**Option 1: Use CSS Variables (Recommended)**
```blade
<button style="background-color: var(--color-primary); color: white;">
```

**Option 2: Keep CDN Tailwind Static**
- Accept that colors are static in CDN mode
- Document that for dynamic colors, need to build Tailwind

**Option 3: Build Tailwind Properly**
- Remove CDN
- Use proper Vite + Tailwind build
- Custom colors will work

## Recommendation

For immediate fix: Update footer to use CSS variables instead of Tailwind classes.
For long-term: Migrate to proper Tailwind build (not CDN).
