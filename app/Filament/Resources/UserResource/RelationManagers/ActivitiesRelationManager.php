<?php


namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')->label('Hành động'),
                Tables\Columns\TextColumn::make('created_at')->label('Thời gian')->since(),
                Tables\Columns\TextColumn::make('subject_type')->label('Đối tượng'),
                Tables\Columns\TextColumn::make('subject_id')->label('ID đối tượng'),
            ])
     
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
