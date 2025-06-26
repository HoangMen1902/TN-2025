<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\UserResource\Widgets\UserInterestStats;
use App\Filament\Resources\UserResource\Widgets\UserChart;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class EditUser extends EditRecord
{
    use ExposesTableToWidgets, WithPagination;
    protected static string $resource = UserResource::class;
    public $activeTab = 'default';
    public array $tableColumnSearches = [];
    public array $tableFilters = [];
    public array $tableGrouping = [];
    public array $tableGroupingDirection = [];
    public array $tableRecordsPerPage = [];
    public array $tableSearch = [];
    public array $tableSortColumn = [];
    public array $tableSortDirection = [];

    protected function afterSave(): void
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->log('Cập nhật thông tin người dùng: ' . $this->record->name);
    }

 
}
