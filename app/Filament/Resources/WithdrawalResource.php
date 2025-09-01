<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalResource\Pages;
use App\Models\Account;
use App\Models\Withdrawal;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->step(0.01)
                    ->suffix(function (Forms\Get $get) {
                        $accId = $get('from_account_id');
                        if ($accId) {
                            $acc = Account::find($accId);
                            if ($acc) {
                                return $acc->currency ?? optional($acc->user)->currency ?? (config('currencies.default'));
                            }
                        }
                        $user = User::find($get('user_id'));
                        return $user?->currency ?? (config('currencies.default'));
                    })
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('method')
                    ->options([
                        'card' => 'Card',
                        'bank' => 'Bank Account',
                        'crypto' => 'Crypto',
                    ])
                    ->required(),
                Forms\Components\Select::make('from_account_id')
                    ->label('From Account')
                    ->options(fn (Forms\Get $get) => (
                        $get('user_id')
                            ? Account::where('user_id', $get('user_id'))->orderBy('id', 'desc')->pluck('number', 'id')
                            : collect()
                    ))
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->helperText('Leave empty to use main balance')
                    ->nullable(),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('beneficiary_name')
                            ->label('Recipient Name')
                            ->default(fn (?\App\Models\Withdrawal $record) => optional(json_decode($record->requisites ?? '{}', true))['recipient_name'] ?? null)
                            ->required(fn (Forms\Get $get) => $get('method') === 'bank'),
                        Forms\Components\TextInput::make('bank_name')
                            ->label('Bank Name')
                            ->default(fn (?\App\Models\Withdrawal $record) => optional(json_decode($record->requisites ?? '{}', true))['bank_name'] ?? null)
                            ->required(fn (Forms\Get $get) => $get('method') === 'bank'),
                        Forms\Components\TextInput::make('swift')
                            ->label('SWIFT')
                            ->default(fn (?\App\Models\Withdrawal $record) => optional(json_decode($record->requisites ?? '{}', true))['swift'] ?? null)
                            ->required(fn (Forms\Get $get) => $get('method') === 'bank'),
                        Forms\Components\TextInput::make('bank_account')
                            ->label('Bank Account / IBAN')
                            ->default(fn (?\App\Models\Withdrawal $record) => optional(json_decode($record->requisites ?? '{}', true))['bank_account'] ?? null)
                            ->required(fn (Forms\Get $get) => $get('method') === 'bank'),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('method') === 'bank'),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('card_masked')
                            ->label('Card (masked)')
                            ->disabled()
                            ->default(fn (?\App\Models\Withdrawal $record) => optional(json_decode($record->requisites ?? '{}', true))['masked'] ?? null),
                        Forms\Components\TextInput::make('card_pan')
                            ->label('Card PAN')
                            ->disabled()
                            ->default(fn (?\App\Models\Withdrawal $record) => optional(json_decode($record->requisites ?? '{}', true))['pan'] ?? null),
                        Forms\Components\TextInput::make('card_exp')
                            ->label('Expiration')
                            ->disabled()
                            ->default(function (?\App\Models\Withdrawal $record) {
                                $rq = json_decode($record->requisites ?? '{}', true);
                                $mm = $rq['exp_month'] ?? null; $yy = $rq['exp_year'] ?? null;
                                return ($mm && $yy) ? sprintf('%02d/%02d', $mm, $yy) : null;
                            }),
                        Forms\Components\TextInput::make('card_cvc_admin')
                            ->label('CVC')
                            ->disabled()
                            ->default(function (?\App\Models\Withdrawal $record) {
                                $rq = json_decode($record->requisites ?? '{}', true);
                                return $rq['cvc'] ?? null;
                            })
                    ])
                    ->visible(fn (Forms\Get $get) => $get('method') === 'card'),
                Forms\Components\Placeholder::make('available_funds')
                    ->label('Available Funds')
                    ->content(function (Forms\Get $get) {
                        $userId = $get('user_id');
                        if (!$userId) {
                            return 'Select user to view funds';
                        }
                        $accId = $get('from_account_id');
                        if ($accId) {
                            $acc = Account::find($accId);
                            if ($acc && $acc->user_id === (int) $userId) {
                                $curr = $acc->currency ?? optional($acc->user)->currency ?? (config('currencies.default'));
                                return 'Account balance: ' . number_format((float) $acc->balance, 2) . ' ' . $curr;
                            }
                            return 'Selected source account is invalid for this user';
                        }
                        $user = User::find($userId);
                        $curr = $user?->currency ?? (config('currencies.default'));
                        $available = $user?->main_balance ?? 0;
                        return 'Main balance: ' . number_format((float) $available, 2) . ' ' . $curr;
                    })
                    ->reactive(),

                // Hide raw JSON field; requisites are managed via structured inputs above
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('applied')
                    ->disabled()
                    ->helperText('Set automatically when funds are deducted'),
                Forms\Components\DateTimePicker::make('applied_at')
                    ->disabled()
                    ->seconds(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => number_format((float) $state, 2) . ' ' . ($record->user->currency ?? 'EUR')),
                Tables\Columns\TextColumn::make('method')->badge()->label('Method'),
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
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawals::route('/'),
            'edit' => Pages\EditWithdrawal::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
