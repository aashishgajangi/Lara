<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\SeoService;

class CategoryController extends Controller
{
    protected $seoService;

    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    public function index()
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id') // Top level categories
            ->orderBy('sort_order')
            ->get();

        $this->seoService->setDefaultSeo('All Categories', 'Browse our wide range of cake categories.', 'website');

        return view('categories.index', compact('categories'));
    }
}
