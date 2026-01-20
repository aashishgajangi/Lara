@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    @livewire('product-detail', ['productSlug' => $slug])
</div>
@endsection
