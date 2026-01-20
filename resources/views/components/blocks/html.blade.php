{{-- Custom HTML Block --}}
<section class="py-12">
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }}">
        @if(!empty($content['html']))
            {!! $content['html'] !!}
        @endif
    </div>
</section>
