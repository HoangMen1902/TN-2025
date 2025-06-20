<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SearchHistory;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
class SearchBox extends Component
{
    public $query = '';
    public $suggestions = [];
    public $histories = [];
    public $productSuggestions = [];

    public function mount()
    {
        if (Auth::check()) {
            $this->histories = SearchHistory::where('user_id', Auth::id())
                ->orderByDesc('updated_at')->limit(10)->pluck('keyword')->toArray();
        }
    }

    public function updatedQuery()
    {
        $this->suggestions = [];
        $this->productSuggestions = [];

        if (strlen($this->query) >= 2) {
            if (Auth::check()) {
                $this->suggestions = SearchHistory::where('user_id', Auth::id())
                    ->where('keyword', 'like', '%' . $this->query . '%')
                    ->orderByDesc('updated_at')->limit(5)->pluck('keyword')->toArray();
            }

            $this->productSuggestions = Product::where('product_status', 'active')
                ->where('name', 'like', '%' . $this->query . '%')
                ->limit(5)->pluck('name')->toArray();
        }
    }

public function search($term = null)
{
    $searchTerm = $term ?? $this->query;

    if (!empty($searchTerm) && Auth::check()) {
        SearchHistory::updateOrCreate(
            ['user_id' => Auth::id(), 'keyword' => $searchTerm],
            ['updated_at' => now()]
        );
         activity()
            ->causedBy(Auth::user())
            ->withProperties(['keyword' => $searchTerm])
            ->log('Tìm kiếm sản phẩm');
    }

    $this->dispatch('searchUpdated', $searchTerm);


    return redirect()->route('store', ['search' => $searchTerm]);
}



    public function render()
    {
        return view('livewire.search-box');
    }
}
