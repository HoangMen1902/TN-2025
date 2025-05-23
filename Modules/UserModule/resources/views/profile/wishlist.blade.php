<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col px-4 md:px-0">
        <div class="flex flex-col md:flex-row md:h-full w-full">
            <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
                <x-usermodule::sidebar />
            </div>

            <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
                <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
                    <h1 class="text-lg md:text-2xl font-medium">Sản phẩm yêu thích</h1>
                </div>

                <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">

                <div class="mb-4 border-b border-gray-200 relative">
                    <div class="flex overflow-x-auto scrollbar-hide" role="tablist">
                        <ul class="flex flex-nowrap whitespace-nowrap min-w-full">
                            <li role="presentation">
                                <button class="inline-block text-red-500 p-4 border-b-2 border-red-500 rounded-t-lg"
                                    id="all-tab" type="button" role="tab" aria-controls="all" aria-selected="true">
                                    Tất cả
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <livewire:usermodule::component.wishlist  />
            </div>
        </div>
    </div>

    <style>
        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thumb-gray-300 {
            scrollbar-color: #d1d5db #f3f4f6;
        }

        .scrollbar-track-gray-100 {
            background: #f3f4f6;
        }
    </style>
</x-layouts.layout>