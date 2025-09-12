<main class="w-full max-w-[1200px] mx-auto px-4 py-8">
    <form method="POST" action="{{ route('checkout.store') }}" id="paymentForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Delivery Options -->
                <livewire:paymentmodule::components.delivery :carts="$carts" />

                <!-- Shipping Address -->
                <section class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Địa chỉ giao hàng</h2>
                    </div>  

                    {{-- Hiển thị địa chỉ có sẵn --}}
                    {{-- <livewire:paymentmodule::components.pick-address /> --}}

                    {{-- Hiển thị form nhập địa chỉ mới --}}
                    <div class="mt-6 border-t pt-4">
                        <livewire:paymentmodule::components.address />
                    </div>
                </section>
                <livewire:paymentmodule::components.payment-method />
            </div>
            <!-- Order Summary -->
            <livewire:paymentmodule::components.summary :carts="$carts"/>
        </div>
    </form>
</main>