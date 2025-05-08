{{-- @extends('productmodule::layouts.master')
@section('content')
    <div class="wrapper">
        <div class="sidebar">
            <x-productmodule::sidebar-filter />
        </div>
        <div class="main-content">
            <x-productmodule::sort-header />

            <x-productmodule::pagination />
        </div>
    </div>
@endsection --}}



<x-layouts.layout>
    @extends('productmodule::layouts.master')
    @section('content')
        <div class="wrapper">
            <div class="sidebar">
                <x-productmodule::sidebar-filter />
            </div>
            <div class="main-content">
                <x-productmodule::sort-header />
                <x-productmodule::product-card />
                <x-productmodule::pagination />
            </div>
        </div>
    @endsection
    
</x-layouts.layout>