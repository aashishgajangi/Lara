# Temporary Image Upload Solution

Due to persistent "waiting for size" issues with Filament's FileUpload component, I've implemented a **manual upload approach**.

## How to Add Images

### For Categories:
1. Upload your image file to: `storage/app/public/categories/2026/01/04/`
2. Use SEO-friendly names: `dairy-products.jpg`
3. In admin, enter path: `categories/2026/01/04/dairy-products.jpg`

### For Products:
1. Upload your image file to: `storage/app/public/products/2026/01/04/`
2. Use SEO-friendly names: `organic-milk.jpg`
3. In admin, add image with path: `products/2026/01/04/organic-milk.jpg`

## Directory Structure:
```
storage/app/public/
├── categories/
│   └── 2026/01/04/
│       └── your-category-images.jpg
└── products/
    └── 2026/01/04/
        └── your-product-images.jpg
```

## Access Via Web:
Images are accessible at: `http://127.0.0.1:8000/storage/categories/2026/01/04/image.jpg`

## Future Enhancement:
This will be replaced with proper drag-and-drop upload in Phase 6 (Performance Optimizations) with a custom upload solution that bypasses Filament's problematic FileUpload component.
