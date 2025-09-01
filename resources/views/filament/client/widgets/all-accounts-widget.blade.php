<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">All Accounts</x-slot>

        @if ($accounts && $accounts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($accounts as $acc)
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Account Number</div>
                            @if ($acc->is_default)
                                <span class="px-2 py-0.5 text-xs rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">Primary</span>
                            @endif
                        </div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $acc->number }}</div>

                        <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Organization</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $acc->organization ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Beneficiary</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $acc->beneficiary ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Investment Control</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $acc->investment_control ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Type</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ config('accounts.types')[$acc->type] ?? $acc->type }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Balance</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $acc->currency ?? 'EUR' }} {{ number_format($acc->balance, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Expiration</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ optional($acc->term)->format('d.m.Y') ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Status</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $acc->status }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400">No accounts yet.</p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>

