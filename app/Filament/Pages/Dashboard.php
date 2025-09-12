<?php


namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Illuminate\Support\Carbon;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('')->schema([
                DatePicker::make('startDate')
                    ->label('Ngày bắt đầu')
                    ->default(Carbon::now()->startOfMonth()->toDateString()),
                DatePicker::make('endDate')
                    ->label('Ngày kết thúc')
                    ->default(Carbon::now()->toDateString()),
            ])->columns(2)
        ]);
    }
    

}
