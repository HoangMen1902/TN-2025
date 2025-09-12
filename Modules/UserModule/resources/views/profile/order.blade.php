<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col px-4 md:px-0">
        <div class="flex flex-col md:flex-row md:h-full w-full">
            <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
                <x-usermodule::sidebar></x-usermodule::sidebar>
            </div>

            <livewire:usermodule::components.order />

        </div>
    </div>

</x-layouts.layout>


<script>
   
</script>