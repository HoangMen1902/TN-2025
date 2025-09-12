<x-layouts.layout>
    <section class="bg-gradient-to-r from-yellow-400 via-pink-300 to-cyan-300 py-20 text-center">
        <h1 class="text-4xl md:text-5xl font-semibold bg-white inline-block px-10 py-4 rounded-lg shadow-lg">
            Liên hệ với chúng tôi
        </h1>
    </section>

    <section class="bg-white py-16 px-4 md:px-10">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 text-gray-800">

            <div>
                <h2 class="text-xl font-semibold mb-2">Trung tâm hỗ trợ</h2>
                <p>Bạn cần giúp đỡ khi đặt sách? Gặp lỗi đơn hàng? Hãy xem phần Câu hỏi thường gặp hoặc <a href="#"
                        class="text-yellow-600 font-medium underline">liên hệ chúng tôi</a> để được hỗ trợ.</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">Câu chuyện thành công</h2>
                <p>Chúng tôi rất vui khi nhận được phản hồi từ bạn. <a href="#" id="openModal"
                        class="text-yellow-600 font-medium underline">Gửi câu chuyện của bạn</a>
                    để chia sẻ cùng cộng đồng yêu sách!</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">Báo chí & Truyền thông</h2>
                <p>Nếu bạn là phóng viên hoặc nhà báo, vui lòng <a href="#"
                        class="text-yellow-600 font-medium underline">liên hệ tại đây</a>.</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">Báo cáo lỗi hệ thống</h2>
                <p>Nếu bạn phát hiện bất kỳ sự cố kỹ thuật nào, vui lòng <a href="#"
                        class="text-yellow-600 font-medium underline">cho chúng tôi biết</a>.</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">Quảng cáo & Hợp tác</h2>
                <p>Bạn muốn hợp tác hoặc quảng cáo? Hãy <a href="#" class="text-yellow-600 font-medium underline">liên
                        hệ với chúng tôi</a>.</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">Yêu cầu pháp lý</h2>
                <p>Gửi yêu cầu chính thức nếu bạn là đại diện pháp luật qua <a href="#"
                        class="text-yellow-600 font-medium underline">mẫu liên hệ này</a>.</p>
            </div>
        </div>
    </section>
    <div id="modal" class="fixed inset-0 flex items-center justify-center hidden z-50 pointer-events-none">
        <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative pointer-events-auto z-50">

            <button id="closeModal"
                class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            <h2 class="text-xl font-semibold mb-4">Chia sẻ câu chuyện của bạn</h2>

            <form class="space-y-4">
                <div>
                    <label class="block font-medium">Tên của bạn</label>
                    <input type="text" class="w-full border rounded px-3 py-2" placeholder="Nguyễn Văn A" required>
                </div>
                <div>
                    <label class="block font-medium">Email</label>
                    <input type="email" class="w-full border rounded px-3 py-2" placeholder="you@example.com" required>
                </div>
                <div>
                    <label class="block font-medium">Câu chuyện</label>
                    <textarea class="w-full border rounded px-3 py-2" rows="4" placeholder="Câu chuyện của bạn..."
                        required></textarea>
                </div>
                <div class="text-right">
                    <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Gửi</button>
                </div>
            </form>
        </div>
    </div>
    <section class="bg-gray-100 py-12 px-4 md:px-10">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-2xl font-semibold mb-6">Vị trí của chúng tôi</h2>
            <div class="w-full h-[450px]">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15717.680901142583!2d105.75232580460924!3d9.982103813414948!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a08906415c355f%3A0x416815a99ebd841e!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1746684344252!5m2!1svi!2s"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <x-slot name="scripts">
        <script>
            const openModal = document.getElementById('openModal');
            const closeModal = document.getElementById('closeModal');
            const modal = document.getElementById('modal');

            openModal.addEventListener('click', function (e) {
                e.preventDefault();
                modal.classList.remove('hidden');
            });

            closeModal.addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        </script>

    </x-slot>

</x-layouts.layout>