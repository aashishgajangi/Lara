@props(['data'])

@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp

<section class="py-6 md:py-12 px-4">
    <div class="{{ $containerClass }}">
        <div class="prose mx-auto {{ ($data['content_width'] ?? 'narrow') === 'narrow' ? 'max-w-4xl' : 'max-w-none' }}">
            {!! $data['content'] ?? '' !!}
        </div>
    </div>
    
    <style>
        .prose > :first-child {
            margin-top: 0;
        }
        .prose h1 {
            font-size: 2.25rem; /* 36px */
            line-height: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #111827;
        }
        .prose h2 {
            font-size: 1.875rem; /* 30px */
            line-height: 2.25rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .prose h3 {
            font-size: 1.5rem; /* 24px */
            line-height: 2rem;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .prose p {
            font-size: 1.125rem; /* 18px */
            line-height: 1.75rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .prose ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .prose ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .prose blockquote {
            border-left-width: 4px;
            border-color: #3b82f6; /* primary-500 approx */
            padding-left: 1rem;
            font-style: italic;
            color: #374151;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</section>
