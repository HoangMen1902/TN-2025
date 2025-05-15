
<x-layouts.layout>
     <x-productmodule::breadcrumbs />
        <div class="container m-auto flex w-[1200px] gap-[5px]">
            <div class="flex p-2 rounded-l  bg-light w-[300px]">
                <x-productmodule::sidebar-filter />
            </div>
            <div class="main-content rounded-r">
                <x-productmodule::sort-header />
               
                <x-productmodule::product-card />
                <x-productmodule::pagination />
            </div>
        </div>
    
</x-layouts.layout>