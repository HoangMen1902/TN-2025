{{-- filepath: Modules/CartModule/resources/views/index.blade.php --}}
<x-layouts.layout>
    <x-slot name="title">BeeBook - Giỏ hàng </x-slot>
    <div
        class="cart-container mx-auto my-5 w-full max-w-[1200px] bg-white p-2 sm:p-5 shadow-md flex flex-col items-stretch rounded-xl">
        <div class="cart flex-1 flex flex-col bg-white p-2 sm:p-5 border border-gray-300 mb-5 lg:mb-0 lg:mr-5">
            <livewire:cartmodule::component.cart />
        </div>
    </div>
    <x-slot name="scripts">
        <script>
            $(document).ready(function () {
                $('input[name="cart_id[]"]').on('change', function () {
                    Livewire.dispatch('selected_cart')
                });

                $('input[name="cart_sku[]"]').on('change', function () {
                    Livewire.dispatch('selected_cart')
                });
                $('input[name="cart_combo[]"]').on('change', function () {
                    Livewire.dispatch('selected_cart')
                });
            });
        </script>
    </x-slot>
</x-layouts.layout>