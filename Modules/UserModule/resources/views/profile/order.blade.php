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
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.rating-star');
        const ratingText = document.getElementById('rating-text');
        const ratingTexts = [
            'Rất không hài lòng',
            'Không hài lòng',
            'Bình thường',
            'Hài lòng',
            'Cực kỳ hài lòng'
        ];

        stars.forEach(star => {
            star.addEventListener('click', function () {
                const rating = parseInt(this.getAttribute('data-rating'));

                stars.forEach(s => {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                });

                for (let i = 0; i < rating; i++) {
                    stars[i].classList.remove('text-gray-300');
                    stars[i].classList.add('text-yellow-400');
                }

                ratingText.textContent = ratingTexts[rating - 1];
            });
        });

        const ratingTags = document.querySelectorAll('.rating-tag');
        ratingTags.forEach(tag => {
            tag.addEventListener('click', function () {
                this.classList.toggle('bg-blue-100');
                this.classList.toggle('border-red-300');
                this.classList.toggle('text-red-500');
            });
        });

        const submitButton = document.getElementById('submit-rating');
        submitButton.addEventListener('click', function () {
            const selectedStars = document.querySelectorAll('.rating-star.text-yellow-400').length;
            const selectedTags = Array.from(document.querySelectorAll('.rating-tag.bg-blue-100')).map(tag => tag.textContent);
            const comment = document.getElementById('review-comment').value;
            const isAnonymous = document.getElementById('anonymous-checkbox').checked;

            // Close modal
            const modal = document.getElementById('rating-modal');
            if (typeof window.Flowbite !== 'undefined') {
                const modalInstance = window.Flowbite.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }

        });
    });
</script>