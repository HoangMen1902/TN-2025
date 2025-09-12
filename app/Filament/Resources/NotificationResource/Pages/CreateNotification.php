<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;

    protected function afterCreate(): void
    {
        $notification = $this->record;
        $userIds = $this->form->getState()['user_ids'] ?? [];

        // Gắn thông báo cho user
        foreach ($userIds as $userId) {
            \App\Models\UserNotification::create([
                'user_id' => $userId,
                'notification_id' => $notification->id,
                'notification_status' => 'unread',
            ]);
        }

        // Nếu có voucher, gắn voucher cho user luôn
        $voucherId = $this->form->getState()['voucher_id'] ?? null;
        if ($voucherId) {
            foreach ($userIds as $userId) {
                \App\Models\VoucherUsed::create([
                    'voucher_id' => $voucherId,
                    'user_id' => $userId,
                    'used_at' => null,
                ]);
            }
        }
    }
}
