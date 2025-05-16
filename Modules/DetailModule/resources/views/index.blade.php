<x-layouts.layout>
    <x-slot name="styles">
        @vite('Modules/DetailModule/Resources/assets/js/app.js')
    </x-slot>
    <div class="m-auto lg:py-lg-2">
        <div class="flex justify-center">
            <div>
                <div class="detail-wrapper flex flex-col sm:flex-col md:flex-col lg:flex-row mt-10 gap-4 lg:py-4">
                    <x-detailmodule::thumbnail :data="$data"></x-detailmodule::thumbnail>
                    <div class="flex flex-col detail-information-wrapper">
                        <livewire:detailmodule::components.general-information :id="$id">
                        </livewire:detailmodule::components.general-information>
                        <livewire:detailmodule::components.shipment-calculate>
                        </livewire:detailmodule::components.shipment-calculate>
                        <x-detailmodule::detail-information :data="$data"></x-detailmodule::detail-information>
                        <x-detailmodule::description :data="$data"></x-detailmodule::description>
                    </div>
                </div>
                <div>
                    <livewire:detailmodule::components.comment>
                    </livewire:detailmodule::components.comment>
                </div>
            </div>
        </div>
    </div>
    <x-detailmodule::add-to-cart-responsive></x-detailmodule::add-to-cart-responsive>






    <x-slot name="scripts">

        <script>
            document.addEventListener('livewire:load', function () {
                Livewire.on('showModal', () => {
                    document.getElementById('modalOverlay').classList.remove('hidden');
                });
            });
        </script>
        <script>
            const minusBtn = document.querySelector(".minus");
            const plusBtn = document.querySelector(".plus");
            const quantityInput = document.getElementById("quantity");

            minusBtn.addEventListener("click", () => {
                let current = parseInt(quantityInput.value);
                if (current > parseInt(quantityInput.min)) {
                    quantityInput.value = current - 1;
                }
            });

            plusBtn.addEventListener("click", () => {
                quantityInput.value = parseInt(quantityInput.value) + 1;
            });
        </script>
        <script>
            $('.view-more-btn').on('click', function () {
                if ($(this).data('mode') === "more") {
                    $(this).text('Rút gọn');
                    $(this).data('mode', "limit");
                    $('.desc-content').css('max-height', 'none');
                    $('.desc-wrapper').css('max-height', 'none');
                    $('.desc-gradient').css('display', 'none');
                } else {

                    $(this).text('Xem thêm');
                    $(this).data('mode', "more");
                    $('.desc-content').css('max-height', '300px');
                    $('.desc-wrapper').css('max-height', '320px');
                    $('.desc-gradient').css('display', 'none');
                }
            });
        </script>
        <script>
            const openBtns = document.querySelectorAll(".change-address");
            const closeBtn = document.getElementById("closeModalBtn");
            const cancelBtn = document.getElementById("cancelBtn");
            const modal = document.getElementById("modalOverlay");

            function closeModal() {
                modal.classList.add("hidden");
            }

            openBtns.forEach(btn => {
                btn.addEventListener("click", () => {
                    modal.classList.remove("hidden");
                });
            });

            closeBtn.addEventListener("click", closeModal);
            cancelBtn.addEventListener("click", closeModal);

            modal.addEventListener("click", (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById('provinceName').addEventListener('change', function () {
                    Livewire.dispatch('updateProvince');
                });

                document.getElementById('districtName').addEventListener('change', function () {
                    Livewire.dispatch('updateDistrict');
                });
            });
        </script>

    </x-slot>
</x-layouts.layout>