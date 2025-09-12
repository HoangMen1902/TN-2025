<?php

// namespace Modules\HomeModule\Livewire\Components;

// use App\Models\Publisher;
// use Livewire\Component;

// class PublisherProduct extends Component
// {
//     public $data;

//     public function mount() {
//         $this->data = $this->data = Publisher::where('publisher_status', 'active')
//         ->has('products')
//         ->with('products')
//         ->limit(3)
//         ->orderBy('created_at', 'desc')
//         ->get();;
//     }
//     public function render()
//     {
//         return view('homemodule::livewire..components.publisher-product');
//     }
// }

namespace Modules\HomeModule\Livewire\Components;

use App\Models\Publisher;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\ProductEbook;
use Livewire\Attributes\Computed; 

class PublisherProduct extends Component
{
    public $displayLimit = 10;

    #[Computed]
    public function publishersAndEbooks()
    {
        $publishers = Publisher::where('publisher_status', 'active')
            ->has('products')
            ->with('products')
            ->limit(3)
            ->orderBy('created_at', 'desc')
            ->get();

        $ebooksData = ProductEbook::orderBy('created_at', 'desc')
                                    ->limit($this->displayLimit)
                                    ->get()
                                    ->map(function ($ebook) {
                                        $price = $ebook->price;
                                        $salePrice = $ebook->sale_price ?? $price;
                                        $isSale = ($ebook->sale_price && $ebook->sale_price < $ebook->price) ? true : false;
                                        $percent = ($price > 0 && $isSale) ? (($price - $salePrice) / $price) * 100 : 0;

                                        return (object) [
                                            'id' => $ebook->id,
                                            'name' => $ebook->title,
                                            'slug' => Str::slug($ebook->title),
                                            'thumbnail' => $ebook->cover_image ? asset('storage/' . $ebook->cover_image) : null,
                                            'is_ebook' => true,
                                            'file_format' => pathinfo($ebook->file_path, PATHINFO_EXTENSION) ?? 'PDF',
                                            'productSkus' => collect([(object)[
                                                'price' => $price,
                                                'sale_price' => $salePrice,
                                            ]]),
                                            'sale_price' => $salePrice,
                                            'price' => $price,
                                            'is_sale' => $isSale,
                                            'discount' => round($percent, 2),
                                            'percent_sold' => 0,
                                        ];
                                    });

        if ($ebooksData->isNotEmpty()) {
            $ebookPublisher = (object) [
                'id' => 'ebooks',
                'publisher_name' => 'Sách Điện Tử',
                'products' => $ebooksData
            ];
            $publishers->push($ebookPublisher);
        }

        return $publishers;
    }

    public function render()
    {
        return view('homemodule::livewire.components.publisher-product', [
            'data' => $this->publishersAndEbooks, 
        ]);
    }
}