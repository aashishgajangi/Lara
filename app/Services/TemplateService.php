<?php

namespace App\Services;

class TemplateService
{
    /**
     * Get available templates
     */
    public static function getTemplates(): array
    {
        return [
            'homepage' => [
                'name' => 'Homepage',
                'description' => 'Main landing page with hero, products, and features',
                'sections' => [
                    'hero' => [
                        'label' => 'Hero Banner',
                        'description' => 'Large banner with image, title, and call-to-action',
                        'default_enabled' => true,
                        'fields' => [
                            'title' => ['type' => 'text', 'label' => 'Headline', 'required' => true],
                            'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle'],
                            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
                            'button_url' => ['type' => 'text', 'label' => 'Button URL'],
                            'image' => ['type' => 'media', 'label' => 'Background Image'],
                        ]
                    ],
                    'features' => [
                        'label' => 'Features Section',
                        'description' => 'Three feature boxes (shipping, payment, returns)',
                        'default_enabled' => true,
                        'fields' => []
                    ],
                    'products' => [
                        'label' => 'Featured Products',
                        'description' => 'Display featured products',
                        'default_enabled' => true,
                        'fields' => [
                            'heading' => ['type' => 'text', 'label' => 'Section Heading'],
                            'count' => ['type' => 'number', 'label' => 'Number of Products', 'default' => 8],
                        ]
                    ],
                    'categories' => [
                        'label' => 'Category Grid',
                        'description' => 'Display product categories',
                        'default_enabled' => false,
                        'fields' => [
                            'heading' => ['type' => 'text', 'label' => 'Section Heading'],
                        ]
                    ],
                    'newsletter' => [
                        'label' => 'Newsletter Signup',
                        'description' => 'Email subscription form',
                        'default_enabled' => true,
                        'fields' => [
                            'heading' => ['type' => 'text', 'label' => 'Heading'],
                            'description' => ['type' => 'textarea', 'label' => 'Description'],
                        ]
                    ],
                ],
            ],
            'about' => [
                'name' => 'About Us',
                'description' => 'Company information page',
                'sections' => [
                    'hero' => [
                        'label' => 'Page Header',
                        'description' => 'Page title and intro',
                        'default_enabled' => true,
                        'fields' => [
                            'title' => ['type' => 'text', 'label' => 'Page Title', 'required' => true],
                            'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle'],
                        ]
                    ],
                    'story' => [
                        'label' => 'Our Story',
                        'description' => 'Company story section',
                        'default_enabled' => true,
                        'fields' => [
                            'heading' => ['type' => 'text', 'label' => 'Heading'],
                            'content' => ['type' => 'richtext', 'label' => 'Content'],
                            'image' => ['type' => 'media', 'label' => 'Image'],
                        ]
                    ],
                    'values' => [
                        'label' => 'Our Values',
                        'description' => 'Company values/mission',
                        'default_enabled' => true,
                        'fields' => [
                            'heading' => ['type' => 'text', 'label' => 'Heading'],
                            'content' => ['type' => 'richtext', 'label' => 'Content'],
                        ]
                    ],
                    'cta' => [
                        'label' => 'Call to Action',
                        'description' => 'Contact or shop CTA',
                        'default_enabled' => true,
                        'fields' => [
                            'heading' => ['type' => 'text', 'label' => 'Heading'],
                            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
                            'button_url' => ['type' => 'text', 'label' => 'Button URL'],
                        ]
                    ],
                ],
            ],
            'contact' => [
                'name' => 'Contact Us',
                'description' => 'Contact information and form',
                'sections' => [
                    'hero' => [
                        'label' => 'Page Header',
                        'description' => 'Page title',
                        'default_enabled' => true,
                        'fields' => [
                            'title' => ['type' => 'text', 'label' => 'Page Title', 'required' => true],
                        ]
                    ],
                    'form' => [
                        'label' => 'Contact Form',
                        'description' => 'Contact form',
                        'default_enabled' => true,
                        'fields' => []
                    ],
                    'info' => [
                        'label' => 'Contact Information',
                        'description' => 'Address, phone, email',
                        'default_enabled' => true,
                        'fields' => [
                            'address' => ['type' => 'textarea', 'label' => 'Address'],
                            'phone' => ['type' => 'text', 'label' => 'Phone'],
                            'email' => ['type' => 'text', 'label' => 'Email'],
                        ]
                    ],
                    'map' => [
                        'label' => 'Map',
                        'description' => 'Google Maps embed',
                        'default_enabled' => false,
                        'fields' => [
                            'embed_code' => ['type' => 'textarea', 'label' => 'Map Embed Code'],
                        ]
                    ],
                ],
            ],
        ];
    }

    /**
     * Get template by key
     */
    public static function getTemplate(string $key): ?array
    {
        $templates = self::getTemplates();
        return $templates[$key] ?? null;
    }

    /**
     * Get default sections for template
     */
    public static function getDefaultSections(string $templateKey): array
    {
        $template = self::getTemplate($templateKey);
        if (!$template) {
            return [];
        }

        $sections = [];
        foreach ($template['sections'] as $sectionKey => $section) {
            $sections[$sectionKey] = $section['default_enabled'] ?? false;
        }

        return $sections;
    }

    /**
     * Get default section data for template
     */
    public static function getDefaultSectionData(string $templateKey): array
    {
        $template = self::getTemplate($templateKey);
        if (!$template) {
            return [];
        }

        $data = [];
        foreach ($template['sections'] as $sectionKey => $section) {
            $sectionData = [];
            foreach ($section['fields'] as $fieldKey => $field) {
                $sectionData[$fieldKey] = $field['default'] ?? '';
            }
            $data[$sectionKey] = $sectionData;
        }

        return $data;
    }
}
