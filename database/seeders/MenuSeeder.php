<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Header Menu
        $headerMenu = Menu::create([
            'name' => 'Main Navigation',
            'location' => 'header',
            'description' => 'Primary navigation menu displayed in the header',
            'is_active' => true,
        ]);

        // Header menu items
        $homeItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Home',
            'url' => '/',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $productsItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Products',
            'url' => '/products',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $categoriesItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Categories',
            'url' => '/categories',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $aboutItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'About',
            'url' => '/about',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $contactItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Contact',
            'url' => '/contact',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        // Create Footer Menu
        $footerMenu = Menu::create([
            'name' => 'Footer Links',
            'location' => 'footer',
            'description' => 'Links displayed in the footer area',
            'is_active' => true,
        ]);

        // Footer menu items - Quick Links
        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'About Us',
            'url' => '/about',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Contact',
            'url' => '/contact',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Privacy Policy',
            'url' => '/privacy-policy',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Terms & Conditions',
            'url' => '/terms',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Shipping Info',
            'url' => '/shipping',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Returns',
            'url' => '/returns',
            'sort_order' => 6,
            'is_active' => true,
        ]);
    }
}
