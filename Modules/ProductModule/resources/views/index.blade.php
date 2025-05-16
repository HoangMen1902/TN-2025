
<x-layouts.layout>
     <x-productmodule::breadcrumbs />
        <div class="container m-auto flex w-[1200px] gap-[5px]">
            <div class="flex rounded-l  bg-light">
                <x-productmodule::sidebar-filter />
            </div>
            <div class="main-content rounded-r" style="border-top-left-radius: 0px !important; border-bottom-left-radius: 0px !important;">
                <x-productmodule::sort-header />
               
                <x-productmodule::product-card />
                <x-productmodule::pagination />
            </div>
        </div>
    


</x-layouts.layout>