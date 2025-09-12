<?php

namespace App\Filament\Pages;

use App\Models\Category;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class CategoryPage extends Page
{
    protected static ?string $title = "Danh mục";
    protected static ?string $model = Category::class;

    protected static ?string $modelLabel = 'Danh mục';
    protected static ?string $pluralModelLabel = 'Danh sách danh mục';
    protected static ?string $navigationLabel = 'Danh mục';
    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.category-page';
        public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Chỉ cho phép super_admin và product staff truy cập page này
        return $user->hasAnyRole(['super_admin', 'product staff']);
    }
}
