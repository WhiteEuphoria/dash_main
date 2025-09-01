<?php

namespace App\Support;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountNumber
{
    public static function generate(): string
    {
        // Try to generate the next numeric account number based on the current maximum.
        $next = (int) DB::table('accounts')
            ->selectRaw('MAX(CAST(number as INTEGER)) as max_num')
            ->value('max_num');

        $candidate = max(1, $next + 1);
        $tries = 0;
        while (Account::where('number', (string) $candidate)->exists() && $tries < 1000000) {
            $candidate++;
            $tries++;
        }

        return (string) $candidate;
    }
}

