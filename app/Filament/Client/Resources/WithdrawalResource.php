<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\WithdrawalResource\Pages;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $modelLabel = 'Withdrawal';
    protected static ?string $pluralModelLabel = 'Withdrawals';

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return $user && $user->isFullyEnabled();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label('Withdrawal Amount')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->suffix(fn () => Auth::user()?->currency ?? config('currencies.default')),

                // Card details (client-side only). We will store only masked data in requisites.
                Forms\Components\TextInput::make('card_number')
                    ->label('Card Number')
                    ->required()
                    ->rules(['required','regex:/^[0-9\s]{12,23}$/'])
                    ->extraAttributes([
                        'x-data' => '{}',
                        'x-on:input' => '\n                            let v = $el.value.replace(/\\D/g, "").slice(0,19);\n                            $el.value = v.replace(/(\\d{4})(?=\\d)/g, "$1 ");\n                        ',
                        'inputmode' => 'numeric',
                        'autocomplete' => 'cc-number',
                    ])
                    ->placeholder('4111 1111 1111 1111')
                    ->helperText('Numbers only. We will store only last 4 digits.'),

                Forms\Components\DatePicker::make('card_expiration')
                    ->label('Expiration Date')
                    ->native(false)
                    ->displayFormat('m/Y')
                    ->closeOnDateSelection(true)
                    ->required(),

                Forms\Components\TextInput::make('card_cvc')
                    ->label('CVC')
                    ->required()
                    ->rules(['required','regex:/^[0-9]{3,4}$/'])
                    ->maxLength(4)
                    ->extraAttributes([
                        'x-data' => '{}',
                        'x-on:input' => '$el.value = $el.value.replace(/\\D/g, "").slice(0,4)'
                    ])
                    ->password()
                    ->revealable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => number_format((float) $state, 2) . ' ' . ($record->user->currency ?? 'EUR')),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'В обработке' => 'Pending',
                        'Выполнено', 'approve' => 'Approved',
                        'Отклонено' => 'Rejected',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending', 'В обработке' => 'warning',
                        'approved', 'Выполнено', 'approve' => 'success',
                        'rejected', 'Отклонено' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Request Date')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawals::route('/'),
            'create' => Pages\CreateWithdrawal::route('/create'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }
}
