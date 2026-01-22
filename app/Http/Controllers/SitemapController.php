<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap
     */
    public function index(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $sitemap .= $this->addUrl(route('home'), now(), 'daily', '1.0');

        // Pages
        foreach (Page::where('is_published', true)->cursor() as $page) {
            $sitemap .= $this->addUrl(
                url($page->slug),
                $page->updated_at,
                'weekly',
                '0.8'
            );
        }

        // Categories
        foreach (Category::where('is_active', true)->cursor() as $category) {
            $sitemap .= $this->addUrl(
                route('categories.show', $category->slug),
                $category->updated_at,
                'weekly',
                '0.7'
            );
        }

        // Products
        foreach (Product::where('is_active', true)->cursor() as $product) {
            $sitemap .= $this->addUrl(
                route('products.show', $product->slug),
                $product->updated_at,
                'daily',
                '0.9'
            );
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Add URL to sitemap
     */
    private function addUrl(string $loc, $lastmod = null, string $changefreq = 'weekly', string $priority = '0.5'): string
    {
        $url = '<url>';
        $url .= '<loc>' . htmlspecialchars($loc) . '</loc>';
        
        if ($lastmod) {
            $url .= '<lastmod>' . $lastmod->format('Y-m-d') . '</lastmod>';
        }
        
        $url .= '<changefreq>' . $changefreq . '</changefreq>';
        $url .= '<priority>' . $priority . '</priority>';
        $url .= '</url>';

        return $url;
    }
}
