<div>
    <h3 class="text-lg font-semibold mb-4">Chọn địa chỉ giao hàng</h3>


    <ul class="space-y-3">
        @foreach ($addresses as $address)
            <li class="">
                <label class="flex border p-4 rounded-lg flex items-start gap-4">

                    <input type="radio" name="selected_address" wire:model.live="selectedAddressId"
                        value="{{ $address->id }}|{{ $address->customer_name }}|{{ $address->phone }}|{{ $address->address }}"
                        class="mt-1" />
                    <div>
                        <p><strong>{{ $address->customer_name }}</strong></p>
                        <p>{{ $address->phone }}</p>
                        <p>{{ $address->address }}</p>
                    </div>
                </label>
            </li>
        @endforeach


        @if (session()->has('shipping_address'))
            @php
                $sessionAddress = session('shipping_address');
            @endphp
            <li class="border p-4 rounded-lg flex items-start gap-4 bg-blue-50">
                <input type="radio" id="address-session" name="selected_address"
                    value="session|{{ $sessionAddress['customer_name'] }}|{{ $sessionAddress['phone'] }}|{{ $sessionAddress['full_address'] ?? $sessionAddress['address'] }}"
                    wire:model.live="selectedAddressId" class="mt-1" />

                <label for="address-session" class="cursor-pointer">
                    <p><strong>{{ $sessionAddress['customer_name'] }}</strong> <span class="text-sm text-gray-500">(Địa chỉ
                            mới)</span></p>
                    <p>{{ $sessionAddress['phone'] }}</p>
                    <p>{{ $sessionAddress['full_address'] ?? $sessionAddress['address'] }}</p>
                </label>

            </li>
        @endif
    </ul>
    <div class="mt-4">
        <p>Bạn đã chọn địa chỉ ID: <strong>{{ $selectedAddressId }}</strong></p>
        <pre>{{ var_dump($selectedAddressId) }}</pre>
        <input type="hidden" id="selected-address-id" name="selected_address" value="{{ $selectedAddressId }}">


    </div>

</div>