<x-layouts.layout>
    <div class="container m-auto pt-6">
        <div class="detail-wrapper xl:flex mt-10 gap-4 justify-center">
            <x-detailmodule::thumbnail></x-detailmodule::thumbnail>
            <div class="flex flex-col">
                <livewire:detailmodule::components.general-information>
                </livewire:detailmodule::components.general-information>
                <livewire:detailmodule::components.shipment-calculate>
                </livewire:detailmodule::components.shipment-calculate>
            <x-detailmodule::detail-information></x-detailmodule::detail-information>
            </div>

        </div>
    </div>
</x-layouts.layout>
