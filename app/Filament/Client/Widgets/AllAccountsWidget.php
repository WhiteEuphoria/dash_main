<?php

namespace App\Filament\Client\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class AllAccountsWidget extends Widget
{
    protected static string $view = 'filament.client.widgets.all-accounts-widget';
    protected int|string|array $columnSpan = 'full';

    public ?Collection $accounts = null;

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->isFullyEnabled();
    }

    public function mount(): void
    {
        if (Auth::check()) {
            $this->accounts = Auth::user()->accounts()
                ->orderByDesc('is_default')
                ->orderByDesc('id')
                ->get();
        }
    }
}
