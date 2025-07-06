<?php

namespace App\Filament\Resources\ProductEbookResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('chapter_name')->label('Tên Chương'),
                TextColumn::make('file_path')->label('Đường dẫn'),
                TextColumn::make('start_page')->label('Trang Bắt đầu')->default('-'),
                TextColumn::make('end_page')->label('Trang Kết thúc')->default('-'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}