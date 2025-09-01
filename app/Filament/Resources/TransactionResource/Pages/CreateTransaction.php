<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Account;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Account is required in the form; ensure currency sync
        $userId = $data['user_id'] ?? null;

        // Fill currency from user when missing
        if (empty($data['currency'])) {
            $user = $userId ? User::find($userId) : null;
            $data['currency'] = $user?->currency ?? (config('currencies.default') ?? 'EUR');
        }

        // Normalize amount to 2 decimals
        if (isset($data['amount'])) {
            $data['amount'] = round((float) $data['amount'], 2);
        }

        return $data;
    }
}
