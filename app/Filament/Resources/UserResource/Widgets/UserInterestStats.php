<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\SearchHistory;
use App\Models\OrderDetail;
use App\Models\ProductSku;
use App\Models\Category;
use Filament\Facades\Filament;
class UserInterestStats extends Widget
{
    protected static string $view = 'filament.resources.user-resource.widgets.user-interest-stats';

    public function getUser()
    {
        // Lấy user đang chỉnh sửa từ route
        $record = Filament::getCurrentPanel()->getRequest()->route('record');
        return \App\Models\User::find($record);
    }

    public function getTopKeywordsProperty()
    {
        $user = $this->getUser();
        if (!$user) return collect();

        return SearchHistory::where('user_id', $user->id)
            ->select('keyword')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('keyword')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('keyword');
    }

    public function getTopCategoriesProperty()
    {
        $user = $this->getUser();
        if (!$user) return collect();

        $skuIds = OrderDetail::whereHas('order', fn($q) => $q->where('user_id', $user->id))
            ->pluck('sku_id');
        $productIds = ProductSku::whereIn('id', $skuIds)->pluck('product_id');
        return Category::whereHas('products', fn($q) => $q->whereIn('id', $productIds))
            ->withCount(['products' => fn($q) => $q->whereIn('id', $productIds)])
            ->orderByDesc('products_count')
            ->limit(5)
            ->get();
    }
}
