<x-layouts.layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Danh sách eBook</h1>
        @if ($ebooks->isEmpty())
            <p>Không có eBook nào.</p>
        @else
            <ul>
                @foreach ($ebooks as $ebook)
                    <li>
                        <a href="{{ route('ebooks.show', ['ebookId' => $ebook->id]) }}">
                            {{ $ebook->product->name ?? $ebook->title ?? 'eBook không có tiêu đề' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-layouts.layout>