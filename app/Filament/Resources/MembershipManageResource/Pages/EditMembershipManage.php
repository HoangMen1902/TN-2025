<?php

namespace App\Filament\Resources\MembershipManageResource\Pages;

use App\Filament\Resources\MembershipManageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMembershipManage extends EditRecord
{
    protected static string $resource = MembershipManageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
