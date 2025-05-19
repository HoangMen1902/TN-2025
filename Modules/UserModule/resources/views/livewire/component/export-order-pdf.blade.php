<div x-data="{ pdfUrl: @entangle('pdfUrl') }" x-init="
    $watch('pdfUrl', value => {
        if (value) {
            window.open(value, '_blank');
        }
    });
">
    <button wire:click="exportPdf"
        class="border border-gray-300 text-gray-700 px-4 py-2 rounded text-sm">
        Xuất PDF
    </button>
</div>
