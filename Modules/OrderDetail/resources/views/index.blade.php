<x-layouts.layout>
    <x-slot name="styles">
        <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    </x-slot>
    <div class="container mx-auto max-w-[1200px] my-3 py-6 md:h-[650px] px-8 bg-white rounded-lg">
        <table id="example" class="display">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Hình ảnh</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tiger Nixon</td>
                    <td>System Architect</td>
                    <td>Edinburgh</td>
                    <td>61</td>
                </tr>
            </tbody>
        </table>
    </div>
    <x-slot name="scripts">
        <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
        <script>
            new DataTable('#example');
        </script>
    </x-slot>
</x-layouts.layout>