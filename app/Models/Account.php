<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'number',
        'bank',
        'client_initials',
        'broker_initials',
        'term',
        'status',
        'balance',
        'currency',
        'is_default',
        'beneficiary',
        'investment_control',
        'organization',
    ];

    protected function casts(): array
    {
        return [
            'term' => 'date',
            'balance' => 'decimal:2',
            'is_default' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::saving(function (Account $account): void {
            // If attempting to create a second account for the same user, convert to update of the existing record
            if (!$account->exists && $account->user_id) {
                $existing = static::where('user_id', $account->user_id)->first();
                if ($existing) {
                    $fillable = $account->getFillable();
                    $data = Arr::only($account->getAttributes(), $fillable);
                    unset($data['id']);
                    $account->setAttribute('id', $existing->getKey());
                    $account->exists = true; // force update
                    $account->fill($data);
                }
            }
            // Force account currency to always match owner's currency for consistency
            if ($account->user || $account->user_id) {
                $user = $account->user ?: User::find($account->user_id);
                if ($user && $user->currency && $account->currency !== $user->currency) {
                    $account->currency = $user->currency;
                }
            }
        });
    }
}
