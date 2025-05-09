
<x-layouts.layout>
        <div class="wrapper my-3">
            <div class="sidebar">
                <x-productmodule::sidebar-filter />
            </div>
            <div class="main-content">
                <x-productmodule::sort-header />
                <x-productmodule::product-card />
                <x-productmodule::pagination />
            </div>
        </div>
    
</x-layouts.layout>