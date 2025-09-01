<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'from',
        'to',
        'type',
        'amount',
        'currency',
        'status',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    protected static function booted(): void
    {
        static::saving(function (Transaction $tx): void {
            if ($tx->user || $tx->user_id) {
                $user = $tx->user ?: User::find($tx->user_id);
                if ($user && $user->currency && $tx->currency !== $user->currency) {
                    $tx->currency = $user->currency;
                }
            }
        });
    }
}
