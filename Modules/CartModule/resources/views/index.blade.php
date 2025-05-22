<x-layouts.layout>
    <x-slot name="title">BeeBook - Giỏ hàng </x-slot>
    <div class="cart-container mx-auto my-5 w-4/5 bg-white p-5 shadow-md flex justify-between items-stretch max-w-[1200px] rounded-xl">
        <div class="cart flex-1 flex flex-col bg-white p-5 border border-gray-300 mb-5 lg:mb-0 lg:mr-5">
            <livewire:cartmodule::component.cart />
        </div>
    </div>
</x-layouts.layout>