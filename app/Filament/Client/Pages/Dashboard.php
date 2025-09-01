<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Client\Widgets\BrokerageAccountWidget;
use App\Filament\Client\Widgets\ClientStatsOverview;
use App\Filament\Client\Widgets\TransitAccountWidget;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || !$user->isFullyEnabled()) {
            $this->redirectRoute('filament.client.pages.verification');
        }
    }

    public function getWidgets(): array
    {
        // Show only the primary account card (styled)
        return [
            \App\Filament\Client\Widgets\BrokerageAccountWidget::class,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return $user && $user->isFullyEnabled();
    }
}
