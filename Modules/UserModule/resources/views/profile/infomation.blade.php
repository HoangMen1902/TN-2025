<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col md:flex md:flex-row md:h-[600px] px-4 md:px-0">
        <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
            <x-usermodule::sidebar></x-usermodule::sidebar>
        </div>

        <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
            <div class="mb-4 md:mb-6">
                <h1 class="text-xl md:text-2xl font-medium ml-4">Hồ Sơ Của Tôi</h1>
                <p class="text-gray-600 text-xs md:text-sm ml-4">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                <hr class="border-t border-gray-300 my-3 md:my-4 mx-4">
            </div>

            <livewire:usermodule::profile />

        </div>
    </div>


</x-layouts.layout>


<script>
    function previewAvatar(event) {
        const input = event.target;
        const preview = document.getElementById('avatarPreview');
        const icon = document.getElementById('avatarIcon');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewAvatarMobile(event) {
        const input = event.target;
        const preview = document.getElementById('avatarPreview-mobile');
        const icon = document.getElementById('avatarIcon-mobile');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>