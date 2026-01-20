<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    public function show(string $slug): View|RedirectResponse
    {
        $page = Page::where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Redirect if this is the homepage
        if ($page->is_homepage) {
            return redirect()->route('home', [], 301);
        }

        // Set SEO
        app(\App\Services\SeoService::class)->setPageSeo($page);

        // Prioritize Block System
        if (!empty($page->blocks)) {
            return view('pages.default', compact('page'));
        }

        // Legacy template-based rendering (fallback)
        if ($page->template_type && $page->template_type !== 'custom' && view()->exists("templates.{$page->template_type}")) {
            return view("templates.{$page->template_type}", compact('page'));
        }

        return view('pages.default', compact('page'));
    }

    public function homepage(): View
    {
        // Set homepage SEO
        app(\App\Services\SeoService::class)->setHomepageSeo();

        // Check if there's a page marked as homepage
        $page = Page::where('is_homepage', true)
            ->where('is_published', true)
            ->first();

        if ($page) {
            // Prioritize Block System
            if (!empty($page->blocks)) {
                return view('pages.default', compact('page'));
            }

            // Legacy template-based rendering
            if ($page->template_type && $page->template_type !== 'custom' && view()->exists("templates.{$page->template_type}")) {
                return view("templates.{$page->template_type}", compact('page'));
            }

            // Use show method for other cases
            return $this->show($page->slug);
        }

        // Fallback: No homepage set
        return view('pages.no-homepage');
    }

    private function getTemplate(string $template): string
    {
        $templates = [
            'default' => 'pages.templates.default',
            'full-width' => 'pages.templates.full-width',
            'sidebar' => 'pages.templates.sidebar',
            'landing' => 'pages.templates.landing',
        ];

        return $templates[$template] ?? $templates['default'];
    }
}
