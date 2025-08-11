<x-layouts.layout>
    <x-slot name="styles">
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
            rel="stylesheet" />
    </x-slot>
    <x-imagesearch::form></x-imagesearch::form>

    <x-slot name="scripts">
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script
            src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
        <script
            src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>

        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

        <script>



            FilePond.registerPlugin(
                FilePondPluginFileEncode,
                FilePondPluginImagePreview,
                FilePondPluginFileValidateType,
                FilePondPluginFileValidateSize
            );
            FilePond.create(document.getElementById('dropzone-file'), {
                allowFileEncode: true,
                allowProcess: true,
                instantUpload: true,
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg', 'image/jpeg'],
                labelFileTypeNotAllowed: 'Chỉ chấp nhận PNG hoặc JPG',
                fileValidateTypeLabelExpectedTypes: 'Định dạng hợp lệ: {allTypes}',
                labelIdle: 'Kéo và thả ảnh vào đây hoặc <span class="filepond--label-action">chọn ảnh</span>',
                maxFileSize: '20MB',
                labelMaxFileSizeExceeded: 'File quá lớn',
                labelMaxFileSize: 'Kích thước tối đa là {filesize}',
            });


            function loading() {
                $('.loading').css('display', 'block')
                $('#searchBtn').removeClass('bg-primary');
                $('#searchBtn').addClass('bg-blue-300');
                $('#searchBtn').prop('disabled', true).text('Đang xử lý...');
            }

        </script>

    </x-slot>
</x-layouts.layout>