# Edit Mode Switcher & Layout Guide

**Date:** January 7, 2026  
**Status:** IMPLEMENTED

---

## What Changed

### ✅ **1. Removed Duplicate Menu Items**

**Before:**
- Pages (from PageResource)
- Pages (Simple) (from PageResourceUltraSimple)

**After:**
- Pages (single menu item)

**Action:** Deleted `PageResourceUltraSimple.php`

---

### ✅ **2. Added Edit Mode Switcher**

**New Feature:** Toggle between Simple and Advanced modes within the same form

**Location:** Top of page edit form

**Options:**
- **✨ Simple Mode** - Minimal fields, no SEO tab, no custom template
- **⚙️ Advanced Mode** - All fields, SEO tab, custom block builder

---

## How It Works

### **When Editing a Page:**

```
┌─────────────────────────────────────────┐
│ Edit Mode                               │
│ Choose your editing experience          │
│                                         │
│ [✨ Simple] [⚙️ Advanced]               │
└─────────────────────────────────────────┘
```

**Click to switch modes instantly!**

---

### **Simple Mode:**

**Shows:**
- ✅ Basic Info (Title, Slug)
- ✅ Template Selection (Homepage, About, Contact only)
- ✅ Section Toggles
- ✅ Content Fields
- ✅ Settings (Publish, Homepage)

**Hides:**
- ❌ Custom Template option
- ❌ SEO Tab
- ❌ Advanced options
- ❌ Meta keywords

**Perfect for:** Quick page creation, non-technical users

---

### **Advanced Mode:**

**Shows:**
- ✅ Everything in Simple Mode
- ✅ Custom Template (Block Builder)
- ✅ SEO Tab (Meta title, description, keywords)
- ✅ All advanced options

**Perfect for:** SEO optimization, custom layouts, developers

---

## About 3-Column Layout

**Current Filament Structure:**

```
┌──────────────────────────────────────────────────────┐
│ [Sidebar Menu]  │  [Main Content Area]              │
│                 │                                    │
│ Dashboard       │  ┌──────────────────────────┐    │
│ Pages ←         │  │ Page Edit Form           │    │
│ Products        │  │                          │    │
│ Categories      │  │ [Edit Mode Switcher]     │    │
│ Orders          │  │                          │    │
│                 │  │ [Tabs: Basic, Homepage,  │    │
│                 │  │  About, Contact, SEO,    │    │
│                 │  │  Settings]               │    │
│                 │  │                          │    │
│                 │  │ [Form Fields]            │    │
│                 │  └──────────────────────────┘    │
└──────────────────────────────────────────────────────┘
```

**This is already a 2-column layout:**
1. **Left Column:** Filament sidebar menu
2. **Right Column:** Page edit form

---

## For True 3-Column Layout

**If you want settings in a separate column:**

```
┌────────────────────────────────────────────────────────────┐
│ [Menu] │ [Main Content]        │ [Settings Sidebar]       │
│        │                       │                          │
│ Pages  │ Title: [...]          │ ☑ Published             │
│        │ Template: [Homepage]  │ ☐ Set as Homepage       │
│        │                       │ ☑ Show Title            │
│        │ Hero Banner           │                          │
│        │ Title: [...]          │ [Save]                  │
│        │ Subtitle: [...]       │ [Cancel]                │
└────────────────────────────────────────────────────────────┘
```

**To implement this, you would need:**

1. **Custom Filament Layout** - Requires custom view
2. **Aside Component** - For settings sidebar
3. **Custom CSS** - For 3-column grid

---

## Implementation Options

### **Option A: Use Filament's Built-in Aside** (Recommended)

Filament supports a sidebar for forms:

```php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Main content
        ])
        ->columns([
            'default' => 2,
            'sm' => 3,
            'lg' => 3,
        ]);
}
```

### **Option B: Custom Layout with Grid**

```php
Forms\Components\Grid::make(3)
    ->schema([
        // Column 1: Main content (span 2)
        Forms\Components\Section::make('Content')
            ->columnSpan(2)
            ->schema([...]),
        
        // Column 2: Settings sidebar (span 1)
        Forms\Components\Section::make('Settings')
            ->columnSpan(1)
            ->schema([...]),
    ]),
```

### **Option C: Sticky Settings Sidebar**

```php
Forms\Components\Section::make('Settings')
    ->aside()
    ->schema([
        Forms\Components\Toggle::make('is_published'),
        Forms\Components\Toggle::make('is_homepage'),
    ]),
```

---

## Current Implementation

**What you have now:**

✅ **Edit Mode Switcher** - Toggle Simple/Advanced  
✅ **Single Menu Item** - No duplicates  
✅ **Responsive Layout** - Works on all screens  
✅ **Tab-based Organization** - Clean interface  

**Layout:**
- 2-column: Sidebar + Content (standard Filament)
- Content area uses tabs for organization
- Settings in dedicated tab

---

## Recommendations

### **For Your Use Case:**

**Keep current tab-based layout** because:
1. ✅ Clean and organized
2. ✅ Standard Filament UX
3. ✅ Mobile-friendly
4. ✅ Easy to navigate

**If you really want 3 columns:**

Use **Option C (Sticky Sidebar)** for settings:

```php
// In PageResource.php, replace Settings tab with:
Forms\Components\Section::make('Quick Settings')
    ->aside()
    ->schema([
        Forms\Components\Toggle::make('is_published')
            ->label('Published')
            ->default(true),
        Forms\Components\Toggle::make('is_homepage')
            ->label('Set as Homepage'),
        Forms\Components\Toggle::make('show_title')
            ->label('Show Title')
            ->default(true),
    ]),
```

This creates a sticky sidebar on the right for quick settings.

---

## Testing

### **Test Edit Mode Switcher:**

1. Go to `/admin/pages`
2. Edit any page
3. See "Edit Mode" section at top
4. Click **Simple** - SEO tab disappears, Custom template hidden
5. Click **Advanced** - SEO tab appears, Custom template available

### **Test Single Menu:**

1. Check sidebar
2. Should see only one "Pages" menu item
3. No "Pages (Simple)" duplicate

---

## Summary

**Implemented:**
- ✅ Edit mode switcher (Simple/Advanced)
- ✅ Removed duplicate menu items
- ✅ Dynamic form based on mode
- ✅ SEO tab hidden in simple mode
- ✅ Custom template only in advanced mode

**Current Layout:**
- 2-column: Sidebar + Content
- Tab-based organization
- Clean and standard

**For 3-column layout:**
- Use `.aside()` for sticky settings sidebar
- Or implement custom grid layout
- Both options available if needed

---

**Your page editing is now simpler and more flexible!** 🎉

Switch between Simple and Advanced modes anytime during editing.
