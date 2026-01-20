@props(['data'])
@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp

<section class="py-16 px-4">
    <div class="{{ $containerClass }}">
        @if(!empty($data['heading']))
            <h2 class="text-3xl font-bold text-center mb-12 font-heading text-gray-900">{{ $data['heading'] }}</h2>
        @endif
        <div class="@if(count($data['members']) < 3) flex flex-wrap justify-center gap-8 @else grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 @endif">
            @foreach($data['members'] as $member)
                @php
                    $imageUrl = isset($member['image']) ? \App\Helpers\MediaHelper::resolveUrl($member['image']) : null;
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-lg transition-all duration-300 @if(count($data['members']) < 3) w-full max-w-sm @endif">
                    @if($imageUrl)
                    <div class="w-40 h-40 mx-auto mb-6 relative">
                        <img src="{{ $imageUrl }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover rounded-full shadow-md border-4 border-white">
                    </div>
                    @endif
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $member['name'] }}</h3>
                        <p class="text-primary-600 font-medium mb-4 uppercase tracking-wider text-xs">{{ $member['role'] }}</p>
                        @if(!empty($member['bio']))
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $member['bio'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
