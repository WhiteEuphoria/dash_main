<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Account;
use App\Models\User;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $userId = $data['user_id'] ?? $this->getRecord()->user_id;

        if (empty($data['currency'])) {
            $user = $userId ? User::find($userId) : null;
            $data['currency'] = $user?->currency ?? (config('currencies.default') ?? 'EUR');
        }

        if (isset($data['amount'])) {
            $data['amount'] = round((float) $data['amount'], 2);
        }

        return $data;
    }
}
