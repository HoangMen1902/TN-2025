<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'BeeBook'}}</title>
    @livewireStyles
    @livewireScripts
@vite([
    'resources/scss/app.scss',
    'resources/js/app.js'
])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/embla-carousel/embla-carousel.umd.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"></script>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs"></script>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    @livewireStyles
</head>
{{ $styles ?? '' }}

<body>

    <header class="bg-white py-2 shadow-sm sticky top-0 z-50">

        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="md:flex md:items-center md:justify-between md:flex-wrap md:gap-2 md:py-4">
                <div class="hidden md:block md:logo">
                    <a href="/">
                        <img src="{{ asset('assets/images/logongangtest.jpg') }}" alt="Logo" class="h-[70px]">
                    </a>
                </div>

                <div class="hidden md:block md:category-select md:relative md:group">
                    <button id="category-trigger-desktop" type="button"
                        class="cursor-pointer flex items-center text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-10">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 ml-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </div>

                <div class="hidden md:block w-full md:flex-1 md:max-w-xl md:min-w-[250px]">
                    <livewire:search-box />
                </div>




                <div class="hidden md:flex md:items-center md:gap-6">
                    <div class="text-center">
                        <a href="/mini-game"
                            class="flex flex-col items-center text-gray-600 text-xs hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.712 4.33a9.027 9.027 0 0 1 1.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 0 0-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 0 1 0 9.424m-4.138-5.976a3.736 3.736 0 0 0-.88-1.388 3.737 3.737 0 0 0-1.388-.88m2.268 2.268a3.765 3.765 0 0 1 0 2.528m-2.268-4.796a3.765 3.765 0 0 0-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 0 1-1.388.88m2.268-2.268 4.138 3.448m0 0a9.027 9.027 0 0 1-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0-3.448-4.138m3.448 4.138a9.014 9.014 0 0 1-9.424 0m5.976-4.138a3.765 3.765 0 0 1-2.528 0m0 0a3.736 3.736 0 0 1-1.388-.88 3.737 3.737 0 0 1-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 0 1-1.652-1.306 9.027 9.027 0 0 1-1.306-1.652m0 0 4.138-3.448M4.33 16.712a9.014 9.014 0 0 1 0-9.424m4.138 5.976a3.765 3.765 0 0 1 0-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 0 1 1.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 0 0-1.652 1.306A9.025 9.025 0 0 0 4.33 7.288" />
                            </svg>

                            <span>Mini Game</span>
                        </a>
                    </div>
                    <div class="text-center relative">
                        <a href="/thong-bao"
                            class="flex flex-col items-center text-gray-600 text-xs hover:text-blue-600">
                            <livewire:notification-count />

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 mb-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            <span>Thông Báo</span>
                        </a>
                    </div>
                    <div class="text-center relative">

                        <a href="{{route('cart.index')}}"
                            class="flex flex-col items-center text-gray-600 text-xs hover:text-blue-600">
                            <livewire:cart-count />

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 mb-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            <span>Giỏ Hàng</span>
                        </a>
                    </div>
                    <div x-data="{ open: false }" class="text-center relative">
                        <div @click="open = !open"
                            class="flex flex-col items-center text-gray-600 text-xs hover:text-blue-600 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 mb-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            @auth
                                <span>{{ auth()->user()->name }}</span>
                            @else
                                <span>Tài khoản</span>
                            @endauth
                        </div>

                        <!-- Dropdown -->
                        <div x-show="open" @click.outside="open = false"
                            class="absolute left-1/2 -translate-x-1/2 mt-2 bg-white shadow-md rounded-md w-40 text-sm z-50">
                            <ul class="text-gray-700 py-2">
                                @auth
                                    <li>
                                        <a href="{{ route('infomation') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 text-left w-full">Xem hồ sơ</a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 hover:bg-gray-100">Đăng
                                                xuất</button>
                                        </form>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('show.login') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 text-left w-full">Đăng nhập</a>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>



                </div>


                <div class="md:hidden">

                    <div class="flex justify-center items-center py-2">
                        <div class="logo text-center">
                            <a href="/">
                                <img src="{{ asset('assets/images/logongangtest.jpg') }}" alt="Logo" class="h-8">
                            </a>
                        </div>
                    </div>


                    <div class="flex items-center justify-between py-2">

                        <div class="category-select relative group">
                            <div id="category-trigger-mobile" class="cursor-pointer flex items-center text-gray-600">
                                <button id="category-trigger-desktop" type="button"
                                    data-drawer-target="drawer-top-example" data-drawer-show="drawer-top-example"
                                    data-drawer-placement="top" aria-controls="drawer-top-example"
                                    class="cursor-pointer flex items-center text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-7">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="md:hidden">
                            <livewire:search-box />
                        </div>

                        <div class="flex items-center gap-3">

                            <div class="text-center">
                                <a href="{{route('cart.index')}}"
                                    class="flex flex-col items-center text-gray-600 text-xs hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 mb-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                </a>
                            </div>


                            <div class="text-center">
                                <a href="{{route('infomation')}}"
                                    class="flex flex-col items-center text-gray-600 text-xs hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 mb-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoryDrawer = document.getElementById('drawer-top-example');
        const desktopBtn = document.querySelector('#category-trigger-desktop');
        const mobileBtn = document.querySelector('[data-drawer-show]');
        const drawer = categoryDrawer;

        let isHoveringBtn = false;
        let isHoveringDrawer = false;

        if (desktopBtn) {
            desktopBtn.addEventListener('mouseenter', () => {
                isHoveringBtn = true;
                drawer.classList.remove('hidden');
            });

            desktopBtn.addEventListener('mouseleave', () => {
                isHoveringBtn = false;
                setTimeout(() => {
                    if (!isHoveringBtn && !isHoveringDrawer) {
                        drawer.classList.add('hidden');
                    }
                }, 150);
            });
        }

        drawer.addEventListener('mouseenter', () => {
            isHoveringDrawer = true;
            drawer.classList.remove('hidden');
        });

        drawer.addEventListener('mouseleave', () => {
            isHoveringDrawer = false;
            setTimeout(() => {
                if (!isHoveringBtn && !isHoveringDrawer) {
                    drawer.classList.add('hidden');
                }
            }, 150);
        });

        if (mobileBtn) {
            mobileBtn.addEventListener('click', () => {
                drawer.classList.remove('hidden');
            });
        }

        const categoryItems = document.querySelectorAll('.category-item');
        const defaultContent = document.querySelector('.default-content');
        let activeSubmenu = null;
        let submenuTimer = null;
        let categoryTimer = null;

        function hideAllSubmenus() {
            document.querySelectorAll('.submenu').forEach(menu => {
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                setTimeout(() => {
                    if (menu.style.visibility === 'hidden') {
                        menu.style.display = 'none';
                    }
                }, 200);
            });

            if (defaultContent) {
                defaultContent.style.display = 'flex';
                setTimeout(() => {
                    defaultContent.style.opacity = '1';
                }, 50);
            }

            activeSubmenu = null;
        }

        categoryItems.forEach(item => {
            const submenu = item.querySelector('.submenu');

            item.addEventListener('mouseenter', () => {
                clearTimeout(categoryTimer);
                clearTimeout(submenuTimer);

                document.querySelectorAll('.submenu').forEach(menu => {
                    if (menu !== submenu) {
                        menu.style.opacity = '0';
                        menu.style.visibility = 'hidden';
                        menu.style.display = 'none';
                    }
                });

                if (submenu) {
                    if (defaultContent) {
                        defaultContent.style.opacity = '0';
                        setTimeout(() => {
                            defaultContent.style.display = 'none';
                        }, 200);
                    }

                    submenu.style.display = 'block';
                    setTimeout(() => {
                        submenu.style.opacity = '1';
                        submenu.style.visibility = 'visible';
                    }, 10);

                    activeSubmenu = submenu;
                }
            });

            item.addEventListener('mouseleave', () => {
                categoryTimer = setTimeout(() => {
                    if (submenu && !isMouseOverElement(submenu)) {
                        submenu.style.opacity = '0';
                        submenu.style.visibility = 'hidden';

                        setTimeout(() => {
                            if (submenu.style.visibility === 'hidden') {
                                submenu.style.display = 'none';

                                const visibleSubmenus = document.querySelectorAll('.submenu[style*="visibility: visible"]');
                                if (visibleSubmenus.length === 0) {
                                    if (defaultContent) {
                                        defaultContent.style.display = 'flex';
                                        setTimeout(() => {
                                            defaultContent.style.opacity = '1';
                                        }, 50);
                                    }
                                }
                            }
                        }, 200);
                    }
                }, 100);
            });

            if (submenu) {
                submenu.addEventListener('mouseenter', () => {
                    clearTimeout(categoryTimer);
                    clearTimeout(submenuTimer);
                });

                submenu.addEventListener('mouseleave', () => {
                    submenuTimer = setTimeout(() => {
                        submenu.style.opacity = '0';
                        submenu.style.visibility = 'hidden';
                        setTimeout(() => {
                            submenu.style.display = 'none';
                            if (defaultContent) {
                                defaultContent.style.display = 'flex';
                                setTimeout(() => {
                                    defaultContent.style.opacity = '1';
                                }, 50);
                            }
                        }, 200);
                    }, 200);
                });
            }
        });

        function isMouseOverElement(element) {
            const rect = element.getBoundingClientRect();
            const mouseX = event.clientX;
            const mouseY = event.clientY;

            return mouseX >= rect.left &&
                mouseX <= rect.right &&
                mouseY >= rect.top &&
                mouseY <= rect.bottom;
        }
    });
    const drawerCloseBtn = document.getElementById('drawer-close-btn');
if (drawerCloseBtn) {
    drawerCloseBtn.addEventListener('click', () => {
        drawer.classList.add('hidden');
    });
}
document.addEventListener('DOMContentLoaded', function () {
        const closeBtn = document.getElementById('drawer-close-btn');
        const drawer = document.getElementById('drawer-top-example');

        closeBtn.addEventListener('click', () => {
            drawer.classList.add('hidden');
        });
    });

</script>

    <div id="drawer-top-example"
    class="hidden fixed top-28 left-1/2 transform -translate-x-1/2 z-40 
    w-full max-w-[1200px] sm:w-[95%] md:w-[1000px] lg:w-[1200px]
    h-[90vh] sm:h-[600px] 
    shadow-xl bg-white border border-gray-200 rounded-lg overflow-hidden"
    tabindex="-1" aria-labelledby="drawer-top-label">

    <div class="flex items-center justify-between px-4 sm:px-6 py-3 bg-blue-600 text-white">
        <h2 class="text-base sm:text-lg font-semibold">Danh mục sản phẩm</h2>
        <button id="drawer-close-btn" class="text-white hover:text-gray-200 text-2xl sm:text-xl font-bold" type="button">✕</button>
    </div>

    <div class="flex flex-col sm:flex-row w-full h-[calc(100%-52px)]">
    <!-- Sidebar danh mục cha -->
    <div class="w-full sm:w-1/3 lg:w-1/4 bg-gray-50 border-r border-gray-200 overflow-auto">
        <ul class="category-sidebar divide-y divide-gray-100">
            @foreach ($categories as $parent)
                <li class="category-item">
                    <a href="#"
                        class="block px-4 py-3 text-sm sm:text-base font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-between transition-colors duration-200">

                        <div class="flex items-center gap-3 w-full">
                           <img src="{{ $parent->image ? asset('storage/' . $parent->image) : asset('https://static.thenounproject.com/png/1077596-200.png') }}"
     alt="{{ $parent->name }}"
     class="w-16 h-16 object-cover rounded-md flex-shrink-0 shadow-sm">
                            <span class="truncate">{{ $parent->name }}</span>
                        </div>

                        @if ($parent->children->count())
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        @endif
                    </a>

                    @if ($parent->children->count())
                        <div class="submenu hidden md:absolute top-[52px] left-1/3 lg:left-1/4 md:w-2/3 lg:w-3/4 
                            h-[calc(100%-52px)] bg-white z-10 opacity-0 invisible 
                            transition-opacity duration-200 ease-in-out">
                            <div class="h-full overflow-hidden">
                                <div class="bg-gray-50 px-4 sm:px-6 py-3 border-b border-gray-200">
                                    <h3 class="text-base sm:text-lg font-bold text-blue-600">{{ $parent->name }}</h3>
                                </div>
                                <div class="p-4 sm:p-6 h-[calc(100%-52px)] overflow-y-auto">
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-4">
                                        @foreach ($parent->children as $child)
                                            <a href="{{ route('store-category', ['categorySlug' => $child->slug]) }}"
                                                class="flex items-center gap-3 px-4 py-2 text-sm sm:text-base text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition-colors duration-150">
                                                <img src="{{ $child->image ? asset('storage/' . $child->image) : asset('https://static.thenounproject.com/png/1077596-200.png') }}"
                                                     alt="{{ $child->name }}"
                                                     class="w-14 h-14 object-cover rounded flex-shrink-0 shadow-sm">
                                                <span>{{ $child->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>

</div>

            
        </div>
    </div>



    {{ $slot }}

    <footer class="page bg-white py-8 text-sm text-gray-700 max-w-[1200px] w-full mx-auto rounded-lg px-4">
        <div class="flex flex-col md:flex-row gap-6">
            <div
                class="w-full md:w-[25%] space-y-3 border-b md:border-b-0 md:border-r border-gray-300 md:pr-4 pb-4 md:pb-0">
                <img src="{{ asset('assets/images/logotest1.png') }}" alt="Beebook Logo"
                    class="w-[150px] mx-auto block">

                <div class="px-4 max-w-screen-xl mx-auto">
                    <p class="text-sm md:text-base break-all whitespace-normal">
                        149/20 Đường 30/4, Phường Xuân Khánh, Quận Ninh Kiều, Cần Thơ<br>
                        Công Ty Cổ Phần Phát Hành Sách TP HCM - BeeBook<br>
                    </p>

                    <p class="text-sm md:text-base break-words whitespace-normal mt-2">
                        Beebook.com nhận đặt hàng trực tuyến và giao hàng tận nơi.
                        KHÔNG hỗ trợ đặt mua và nhận hàng trực tiếp tại văn phòng
                        cũng như tất cả Hệ Thống Beebook trên toàn quốc.
                    </p>
                </div>

                <img src="{{ asset('assets/images/logo-bo-cong-thuong-da-thong-bao1.jpg') }}" alt="Đã thông báo BCT"
                    class="w-[120px]">
                <div class="flex items-center gap-2 mt-2">
                    <img src="{{ asset('assets/images/Facebook.jpg') }}" alt="Facebook" class="w-10 ">
                    <img src="{{ asset('assets/images/Instagram.jpg') }}" alt="Instagram" class="w-10">
                    <img src="{{ asset('assets/images/Youtube.jpg') }}" alt="YouTube" class="w-10">
                    <img src="{{ asset('assets/images/Twitter.jpg') }}" alt="Twitter" class="w-10">
                    <img src="{{ asset('assets/images/Tumblr.jpg') }}" alt="TikTok" class="w-10">
                </div>
                <div class="flex gap-2 mt-2">
                    <img src="{{ asset('assets/images/chplay.jpg') }}" alt="Google Play" class="h-8">
                    <img src="{{ asset('assets/images/appstore.jpg') }}" alt="App Store" class="h-8">
                </div>
            </div>


            <div class="w-full md:w-[75%] space-y-6">

                <div class="flex flex-col md:flex-row w-full space-y-6 md:space-y-0 md:space-x-6">
                    <div class="w-full md:w-[33%]">
                        <h4 class="font-semibold mb-2">DỊCH VỤ</h4>
                        <ul class="space-y-2">
                            <li><a href="#">Điều khoản sử dụng</a></li>
                            <li><a href="#">Chính sách bảo mật thông tin cá nhân</a></li>
                            <li><a href="#">Chính sách bảo mật thanh toán</a></li>
                            <li><a href="#">Giới thiệu Beebook</a></li>
                            <li><a href="#">Hệ thống trung tâm - nhà sách</a></li>
                        </ul>
                    </div>
                    <div class="w-full md:w-[33%]">
                        <h4 class="font-semibold mb-2">HỖ TRỢ</h4>
                        <ul class="space-y-2">
                            <li><a href="#">Chính sách đổi - trả - hoàn tiền</a></li>
                            <li><a href="#">Chính sách bảo hành - bồi hoàn</a></li>
                            <li><a href="#">Chính sách vận chuyển</a></li>
                            <li><a href="#">Chính sách khách sỉ</a></li>
                        </ul>
                    </div>
                    <div class="w-full md:w-[33%]">
                        <h4 class="font-semibold mb-2">TÀI KHOẢN CỦA TÔI</h4>
                        <ul class="space-y-2">
                            <li><a href="#">Đăng nhập/Tạo mới tài khoản</a></li>
                            <li><a href="#">Thay đổi địa chỉ khách hàng</a></li>
                            <li><a href="#">Chi tiết tài khoản</a></li>
                            <li><a href="#">Lịch sử mua hàng</a></li>
                        </ul>
                    </div>
                </div>


                <div class="space-y-4">
                    <h4 class="font-semibold">LIÊN HỆ</h4>
                    <div class="flex flex-col md:flex-row gap-4">
                        <p class="flex items-center w-full md:w-[33%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            60-62 Lê Lợi, Q.1, TP. HCM
                        </p>
                        <p class="flex items-center w-full md:w-[33%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            cskh@beebook.com.vn
                        </p>
                        <p class="flex items-center w-full md:w-[33%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            1900636467
                        </p>
                    </div>

                    <div class="hidden md:flex flex-wrap justify-center gap-6 items-center mb-12 px-4 py-8">
                        <img src="{{ asset('assets/images/logo_lex.jpg') }}" alt="LEX" class="h-12 md:h-[70px]">
                        <img src="{{ asset('assets/images/Logo_ninjavan.jpg') }}" alt="Ninja Van"
                            class="h-12 md:h-[70px]">
                        <img src="{{ asset('assets/images/vnpost.jpg') }}" alt="ViettelPost" class="h-12 md:h-[70px]">
                    </div>

                    <div class="hidden md:flex flex-wrap justify-center gap-6 items-center mt-12 px-4 py-8">
                        <img src="{{ asset('assets/images/vnpay_log.jpg') }}" alt="VNPAY" class="h-8 md:h-[40px]">
                        <img src="{{ asset('assets/images/momopay.jpg') }}" alt="MoMo" class="h-8 md:h-[40px]">
                        <img src="{{ asset('assets/images/shopeepay_logo.jpg') }}" alt="ShopeePay"
                            class="h-8 md:h-[40px]">
                        <img src="{{ asset('assets/images/logo_zalopay_2.png') }}" alt="ZaloPay"
                            class="h-8 md:h-[40px]">
                    </div>


                </div>
            </div>
        </div>
        <p class="text-xs mt-10 text-center break-all text-gray-500 leading-relaxed">
            Giấy chứng nhận Đăng ký Kinh doanh số <strong>0304132047</strong> do Sở KH&ĐT TP.HCM cấp ngày
            <strong>20/12/2005</strong>,<br>
            đăng ký thay đổi lần thứ <strong>10</strong>, ngày <strong>07/05/2025</strong>.
        </p>

    </footer>



    <!-- script -->
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
        integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    {{ $scripts ?? ''}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            $(() => {
                if (typeof success === 'function') {
                    success('Thành công', @json(session('error')));
                }
            });
        </script>
    @elseif (session('error'))
        <script>
            $(() => {
                if (typeof danger === 'function') {
                    danger('Thất bại', @json(session('error')));
                }
            });
        </script>
    @endif
    <script>
        Livewire.on('toast', ({
            type,
            message
        }) => {
            console.log('Toast received:', type, message);
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: type, // success, error, warning
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

        });
    </script>
    <div x-data="{ show: false, message: '', type: 'success' }" x-show="show" x-transition x-init="
        Livewire.on('toast', ({ type: t, message: m }) => {
            type = t;
            message = m;
            show = true;
            setTimeout(() => show = false, 3000);
        });
    " class="fixed bottom-4 right-4 z-50 px-4 py-2 rounded text-white text-sm shadow-lg" :class="{
        'bg-green-500': type === 'success',
        'bg-red-500': type === 'danger',
        'bg-yellow-500': type === 'warning',
    }" style="display: none;">
        <span x-text="message"></span>
    </div>
    @livewireScripts
</body>

</html>