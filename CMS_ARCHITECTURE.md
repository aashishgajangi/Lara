# LaraCommerce CMS Architecture

## Overview
Professional e-commerce CMS following industry best practices (Magento/Shopify style) with modular, dynamic approach.

---

## ✅ Completed Features

### 1. **Menu Management System** (Like Magento/Shopify)
**Location:** `/admin/menus`

#### Features:
- **Multiple Menu Locations:** Header, Footer, Mobile, Sidebar
- **Hierarchical Structure:** Support for parent/child menu items (unlimited depth)
- **Drag & Drop Ordering:** Reorder menu items with `sort_order`
- **Dynamic URLs:** Internal links, external links, custom URLs
- **Menu Item Options:**
  - Title
  - URL
  - Target (_self / _blank)
  - Icon (optional)
  - CSS Classes (optional)
  - Active/Inactive status
  - Sort order

#### Database Tables:
- `menus` - Menu containers (header, footer, etc.)
- `menu_items` - Individual menu items with parent-child relationships

#### Usage:
```php
// In Blade templates
$headerMenu = \App\Models\Menu::getByLocation('header');
$footerMenu = \App\Models\Menu::getByLocation('footer');

// Display menu
@foreach($headerMenu->items as $item)
    <a href="{{ $item->url }}">{{ $item->title }}</a>
    @if($item->children->count() > 0)
        // Display submenu
    @endif
@endforeach
```

---

### 2. **Page Management System**
**Location:** `/admin/pages`

#### Features:
- Create unlimited pages (About, Contact, Privacy, etc.)
- 4 Page Templates:
  - **Default:** Standard layout
  - **Full Width:** Hero + full-width content
  - **Sidebar:** Content with sidebar
  - **Landing:** Marketing page with CTA
- Rich text editor for content
- SEO settings (meta title, description, keywords)
- Slug generation
- Publish/Draft status
- Homepage designation

---

### 3. **Site Settings** (For Global Settings)
**Location:** `/admin/site-settings`

#### Current Settings:
- **Logo:** Text or image logo
- **Contact Info:** Phone, email, address
- **Social Media:** Facebook, Twitter, Instagram, LinkedIn
- **Footer:** About text, copyright
- **Hero Section:** Title, subtitle, button, background image

---

## 🎯 Recommended Next Steps

### 1. **Refactor Site Settings**
Instead of one big settings page, create dedicated resources:

#### A. **General Settings Resource**
- Site name
- Logo (text/image)
- Favicon
- Timezone
- Currency

#### B. **Contact Settings Resource**
- Phone numbers
- Email addresses
- Physical addresses
- Business hours

#### C. **Social Media Resource**
- Facebook
- Twitter
- Instagram
- LinkedIn
- YouTube
- TikTok

#### D. **SEO Settings Resource**
- Default meta title
- Default meta description
- Google Analytics ID
- Google Tag Manager
- Facebook Pixel

---

### 2. **Homepage Builder** (Advanced)
Make homepage editable like other pages with sections/widgets:

#### Widget Types:
- Hero Banner
- Featured Products
- Category Grid
- Text Block
- Image Gallery
- Testimonials
- Newsletter Signup
- Custom HTML

#### Implementation:
```php
// Homepage has many sections
$homepage->sections()->create([
    'type' => 'hero',
    'data' => ['title' => '...', 'image' => '...'],
    'sort_order' => 1
]);
```

---

### 3. **Block/Widget System** (Reusable Content)
Create reusable content blocks:

- **Static Blocks:** Reusable HTML/text content
- **Dynamic Blocks:** Product sliders, category lists
- **Placement:** Can be inserted anywhere (pages, homepage, etc.)

---

## 📊 Current Database Structure

```
menus
├── id
├── name
├── location (header, footer, mobile, sidebar)
├── description
├── is_active
└── timestamps

menu_items
├── id
├── menu_id (FK)
├── parent_id (FK - self-referencing)
├── title
├── url
├── target (_self, _blank)
├── icon
├── css_class
├── sort_order
├── is_active
└── timestamps

pages
├── id
├── title
├── slug
├── content
├── template
├── meta_title
├── meta_description
├── meta_keywords
├── is_published
├── is_homepage
└── timestamps

site_settings
├── id
├── key
├── type
├── value
├── group
├── label
├── description
├── sort_order
└── timestamps
```

---

## 🎨 Frontend Integration

### Header
- Dynamic menu from `Menu::getByLocation('header')`
- Logo from site settings
- Contact info from site settings
- Supports dropdown submenus

### Footer
- Dynamic menu from `Menu::getByLocation('footer')`
- Social media links from site settings
- Contact info from site settings
- Copyright text from site settings

### Pages
- Dynamic routing: `/{slug}`
- Template selection
- SEO meta tags injection

---

## 🔧 Admin Panel Structure

```
Content Management
├── Pages (CRUD pages)
├── Menus (Manage all menus)
└── Site Settings (Global settings)

Products
├── Products
├── Categories
└── Reviews

Customers
├── Customers
└── Orders

Settings
├── General Settings
├── Email Settings
└── Payment Settings
```

---

## 💡 Benefits of This Architecture

1. **Separation of Concerns:** Menus, pages, and settings are separate
2. **Flexibility:** Easy to add new menu locations or page templates
3. **Scalability:** Can handle complex menu structures
4. **User-Friendly:** Drag-drop ordering, intuitive interface
5. **Industry Standard:** Follows Magento/Shopify patterns
6. **Developer-Friendly:** Clean models, relationships, and helpers

---

## 🚀 How to Use

### Create a New Menu:
1. Go to `/admin/menus`
2. Click "New Menu"
3. Select location (header/footer)
4. Add menu items with drag-drop ordering
5. Menu automatically appears on frontend

### Create a New Page:
1. Go to `/admin/pages`
2. Click "New Page"
3. Choose template
4. Add content with rich editor
5. Set SEO settings
6. Publish

### Update Site Settings:
1. Go to `/admin/site-settings`
2. Edit logo, contact info, social media
3. Changes reflect immediately

---

## 📝 Notes

- All menus support unlimited nesting (submenus)
- Pages can use different templates
- Homepage can be any page (set `is_homepage = true`)
- All settings have fallback defaults
- Mobile-responsive by default
