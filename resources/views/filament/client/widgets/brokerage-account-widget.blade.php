<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Brokerage Account
        </x-slot>

        @if ($account)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Account Number</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ $account->number }}</div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Organization</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ $account->organization }}</div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Beneficiary</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ $account->beneficiary ?: '—' }}</div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Investment Control</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ $account->investment_control ?: '—' }}</div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Balance</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ $account->currency ?? 'EUR' }} {{ number_format($account->balance, 2) }}</div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Type</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ config('accounts.types')[$account->type] ?? $account->type }}</div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Expiration</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ optional($account->term)->format('d.m.Y') }}</div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ $account->status }}</div>
                </div>
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400">You have no brokerage accounts yet.</p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
