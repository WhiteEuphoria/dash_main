<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;

class Currency extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string $view = 'filament.client.pages.currency';
    protected static ?string $navigationLabel = 'Currency';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public function mount(): void
    {
        abort(403);
    }
}
