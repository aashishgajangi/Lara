<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Page;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('404_error_page')) {
            Schema::create('404_error_page', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        if (!Page::where('slug', '404-error')->exists()) {
            // Create 404 error page
            Page::create([
                'title' => '404 Error Page',
                'slug' => '404-error',
                'template_type' => 'custom',
                'is_published' => true,
                'is_homepage' => false,
                'show_title' => false,
                'section_data' => [
                    'title' => '404 - Page Not Found',
                    'message' => "Sorry, the page you're looking for doesn't exist. It might have been moved or deleted.",
                    'button_text' => 'Go to Homepage',
                    'button_url' => '/',
                    'show_search' => true,
                ],
                'meta_title' => '404 - Page Not Found',
                'meta_description' => 'The page you are looking for could not be found.',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Page::where('slug', '404-error')->delete();
        Schema::dropIfExists('404_error_page');
    }
};
