<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;

class SeoService
{
    /**
     * Set SEO for Product page
     */
    public function setProductSeo(Product $product): void
    {
        $title = $product->seo?->meta_title ?? $product->name;
        $description = $product->seo?->meta_description ?? $product->short_description ?? strip_tags($product->description);
        $image = $product->images->first()?->src ?? null;
        
        $imageUrl = null;
        if ($image) {
            $imageUrl = filter_var($image, FILTER_VALIDATE_URL) ? $image : url($image);
        }

        // Fallback to site logo if no product image
        if (!$imageUrl) {
            $logoPath = \App\Models\SiteSetting::getMediaUrl('site_logo_image');
            if ($logoPath) {
                $imageUrl = filter_var($logoPath, FILTER_VALIDATE_URL) ? $logoPath : url($logoPath);
            }
        }

        // Meta Tags
        SEOMeta::setTitle($title)
            ->setDescription(substr($description, 0, 160))
            ->setKeywords($product->seo?->meta_keywords ?? $product->name)
            ->setCanonical($product->seo?->canonical_url ?? route('products.show', $product->slug))
            ->addMeta('product:price:amount', $product->effective_price)
            ->addMeta('product:price:currency', 'INR');

        // Open Graph
        OpenGraph::setTitle($product->seo?->og_title ?? $title)
            ->setDescription($product->seo?->og_description ?? $description)
            ->setUrl(route('products.show', $product->slug))
            ->setType('product')
            ->addProperty('product:price:amount', $product->effective_price)
            ->addProperty('product:price:currency', 'INR')
            ->addProperty('product:availability', $product->stock_quantity > 0 ? 'in stock' : 'out of stock');

        if ($imageUrl) {
            OpenGraph::addImage($imageUrl);
        }

        // Twitter Card
        TwitterCard::setType($product->seo?->twitter_card ?? 'summary_large_image')
            ->setTitle($product->seo?->og_title ?? $title)
            ->setDescription(substr($description, 0, 200));

        if ($imageUrl) {
            TwitterCard::setImage($imageUrl);
        }

        // Schema.org JSON-LD - Product
        JsonLd::setType('Product')
            ->setTitle($product->name)
            ->setDescription($description)
            ->setUrl(route('products.show', $product->slug))
            ->addValue('sku', $product->sku)
            ->addValue('brand', [
                '@type' => 'Brand',
                'name' => config('app.name')
            ])
            ->addValue('offers', [
                '@type' => 'Offer',
                'url' => route('products.show', $product->slug),
                'priceCurrency' => 'INR',
                'price' => $product->effective_price,
                'priceValidUntil' => now()->addYear()->format('Y-m-d'),
                'availability' => $product->stock_quantity > 0 
                    ? 'https://schema.org/InStock' 
                    : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition'
            ]);

        if ($imageUrl) {
            JsonLd::addImage($imageUrl);
        }

        // Add aggregate rating if reviews exist
        if ($product->reviews()->count() > 0) {
            $avgRating = $product->reviews()->avg('rating');
            $reviewCount = $product->reviews()->count();
            
            JsonLd::addValue('aggregateRating', [
                '@type' => 'AggregateRating',
                'ratingValue' => round($avgRating, 1),
                'reviewCount' => $reviewCount,
                'bestRating' => 5,
                'worstRating' => 1
            ]);
        }

        // Add BreadcrumbList
        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Products', 'url' => route('products.index')],
        ];

        if ($product->category) {
            $breadcrumbs[] = [
                'name' => $product->category->name,
                'url' => route('categories.show', $product->category->slug)
            ];
        }

        $breadcrumbs[] = [
            'name' => $product->name,
            'url' => route('products.show', $product->slug)
        ];

        $this->addBreadcrumbs($breadcrumbs);
    }

    /**
     * Set SEO for Category page
     */
    public function setCategorySeo(Category $category): void
    {
        $title = $category->seo?->meta_title ?? $category->name . ' - Shop Online';
        $description = $category->seo?->meta_description ?? $category->description ?? "Browse our collection of {$category->name}";
        $imageUrl = $category->image ? url(\App\Helpers\MediaHelper::resolveUrl($category->image)) : null;

        // Meta Tags
        SEOMeta::setTitle($title)
            ->setDescription(substr($description, 0, 160))
            ->setKeywords($category->seo?->meta_keywords ?? $category->name)
            ->setCanonical($category->seo?->canonical_url ?? route('categories.show', $category->slug));

        // Open Graph
        OpenGraph::setTitle($category->seo?->og_title ?? $title)
            ->setDescription($category->seo?->og_description ?? $description)
            ->setUrl(route('categories.show', $category->slug))
            ->setType('website');

        if ($imageUrl) {
            OpenGraph::addImage($imageUrl);
        }

        // Twitter Card
        TwitterCard::setType('summary')
            ->setTitle($category->seo?->og_title ?? $title)
            ->setDescription(substr($description, 0, 200));

        if ($imageUrl) {
            TwitterCard::setImage($imageUrl);
        }

        // Schema.org JSON-LD - CollectionPage
        JsonLd::setType('CollectionPage')
            ->setTitle($category->name)
            ->setDescription($description)
            ->setUrl(route('categories.show', $category->slug));

        if ($imageUrl) {
            JsonLd::addImage($imageUrl);
        }

        // Add BreadcrumbList
        $this->addBreadcrumbs([
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Categories', 'url' => route('categories.index')],
            ['name' => $category->name, 'url' => route('categories.show', $category->slug)]
        ]);
    }

    /**
     * Set SEO for CMS Page
     */
    public function setPageSeo(Page $page): void
    {
        $title = $page->meta_title ?? $page->title;
        $description = $page->meta_description ?? strip_tags($page->content ?? '');
        
        // Meta Tags
        SEOMeta::setTitle($title)
            ->setDescription(substr($description, 0, 160))
            ->setKeywords($page->meta_keywords ?? '')
            ->setCanonical(url()->current());

        // Open Graph
        OpenGraph::setTitle($title)
            ->setDescription($description)
            ->setUrl(url()->current())
            ->setType('website');

        // Twitter Card
        TwitterCard::setType('summary')
            ->setTitle($title)
            ->setDescription(substr($description, 0, 200));

        // Schema.org JSON-LD - WebPage
        JsonLd::setType('WebPage')
            ->setTitle($page->title)
            ->setDescription($description)
            ->setUrl(url()->current());
    }

    /**
     * Set SEO for Homepage
     */
    public function setHomepageSeo(): void
    {
        $siteName = config('app.name');
        $title = \App\Models\SiteSetting::get('site_logo_text', $siteName) . ' - Quality Products Online';
        $description = 'Shop quality products at great prices. Fast shipping, secure payment, and easy returns.';

        // Meta Tags
        SEOMeta::setTitle($title)
            ->setDescription($description)
            ->setCanonical(route('home'));

        // Open Graph
        OpenGraph::setTitle($title)
            ->setDescription($description)
            ->setUrl(route('home'))
            ->setType('website')
            ->setSiteName($siteName);

        // Twitter Card
        TwitterCard::setType('summary_large_image')
            ->setTitle($title)
            ->setDescription($description);

        // Schema.org JSON-LD - Organization + WebSite
        $this->addOrganizationSchema();
        $this->addWebSiteSchema();
    }

    /**
     * Add Organization Schema
     */
    protected function addOrganizationSchema(): void
    {
        $logoUrl = \App\Models\SiteSetting::getMediaUrl('site_logo_image');
        
        JsonLd::addValue('organization', [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => url('/'),
            'logo' => $logoUrl ? url($logoUrl) : null,
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => \App\Models\SiteSetting::get('header_phone', ''),
                'contactType' => 'customer service',
                'email' => \App\Models\SiteSetting::get('header_email', '')
            ],
            'sameAs' => array_filter([
                \App\Models\SiteSetting::get('social_facebook'),
                \App\Models\SiteSetting::get('social_twitter'),
                \App\Models\SiteSetting::get('social_instagram'),
                \App\Models\SiteSetting::get('social_linkedin'),
            ])
        ]);
    }

    /**
     * Add WebSite Schema with SearchAction
     */
    protected function addWebSiteSchema(): void
    {
        JsonLd::addValue('website', [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/products?search={search_term_string}')
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ]);
    }

    /**
     * Add BreadcrumbList Schema
     */
    public function addBreadcrumbs(array $items): void
    {
        if (empty($items)) {
            return;
        }

        JsonLdMulti::setType('BreadcrumbList');

        $listItems = [];
        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }

        JsonLdMulti::addValue('itemListElement', $listItems);
    }

    /**
     * Set default SEO for any page
     */
    public function setDefaultSeo(string $title, string $description = '', string $type = 'website'): void
    {
        SEOMeta::setTitle($title)
            ->setDescription($description ?: "Shop quality products at {$title}")
            ->setCanonical(url()->current());

        OpenGraph::setTitle($title)
            ->setDescription($description)
            ->setUrl(url()->current())
            ->setType($type);

        TwitterCard::setType('summary')
            ->setTitle($title)
            ->setDescription($description);
    }
}
