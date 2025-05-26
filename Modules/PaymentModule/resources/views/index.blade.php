<x-layouts.layout>
    <x-paymentmodule::page :carts="$carts"></x-paymentmodule::page>

    <x-slot name="scripts">
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById('province').addEventListener('change', function () {
                    Livewire.dispatch('updateProvince');
                });

                document.getElementById('district').addEventListener('change', function () {
                    Livewire.dispatch('updateDistrict');
                });
            });

            window.addEventListener('address-selected', function (event) {
                console.log('📦 Event address-selected nhận được:', event.detail);

                const data = event.detail;

                const nameField = document.getElementById('customer_name');
                const phoneField = document.getElementById('phone');
                const emailField = document.getElementById('contact_email');

                if (!nameField || !phoneField || !emailField) {
                    console.warn('⛔ Một trong các trường input không tìm thấy!');
                    return;
                }

                nameField.value = data.customer_name;
                phoneField.value = data.phone;
                emailField.value = data.email ?? '';

                console.log('✅ Gán dữ liệu hoàn tất:', data);

                window.dispatchEvent(new CustomEvent('close-address-modal'));
            });
            document.addEventListener('DOMContentLoaded', function () {
                const openBtn = document.getElementById('openModalBtn');
                const closeBtn = document.getElementById('closeModalBtn');
                const modal = document.getElementById('modalOverlay');
                const modalContent = document.getElementById('modalContent');

                openBtn.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                });

                closeBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });

                modal.addEventListener('click', (e) => {
                    if (!modalContent.contains(e.target)) {
                        modal.classList.add('hidden');
                    }
                });
            });

            document.addEventListener('livewire:load', () => {

                document.getElementById('place-order-btn')?.addEventListener('click', () => {
                    const form = document.getElementById('checkout-form');
                    if (form) {
                        form.requestSubmit();
                    } else {
                        console.error('❌ Không tìm thấy form checkout sau khi Livewire load');
                    }
                });
            });
            window.addEventListener('addressSaved', () => {
                const btn = document.getElementById('place-order-btn');
                if (btn) {
                    btn.removeAttribute('disabled');
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });

        </script>


    </x-slot>
</x-layouts.layout>