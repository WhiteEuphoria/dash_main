<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Account;
use Filament\Forms\Get;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DateTimePicker::make('created_at')
                ->label('Date & Time')
                ->required()
                ->seconds(false)
                ->default(fn () => now())
                ->native(false),
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->live()
                ->required(),
            Forms\Components\Select::make('account_id')
                ->label('Account')
                ->options(fn (Get $get) => ($uid = $get('user_id'))
                    ? Account::where('user_id', $uid)->pluck('number', 'id')
                    : Account::pluck('number', 'id'))
                ->searchable()
                ->preload()
                ->required()
                ->live(),
            Forms\Components\TextInput::make('from')->required()->maxLength(255),
            Forms\Components\TextInput::make('to')->required()->maxLength(255),
            Forms\Components\Select::make('type')
                ->options([
                    'classic' => 'classic',
                    'fast' => 'fast',
                    'conversion' => 'conversion',
                    'hold' => 'hold',
                ])->required(),
            Forms\Components\TextInput::make('amount')
                ->numeric()
                ->required()
                ->rules(['numeric','gte:0.01'])
                ->dehydrateStateUsing(function ($state) {
                    if (is_string($state)) {
                        $state = str_replace("\u00A0", '', $state);
                        $state = str_replace(' ', '', $state);
                        $state = str_replace(',', '.', $state);
                    }
                    return round((float) $state, 2);
                })
                ->suffix(function (Get $get) {
                    $accId = $get('account_id');
                    if ($accId) {
                        $acc = Account::find($accId);
                        if ($acc) {
                            return $acc->currency ?? optional($acc->user)->currency ?? (config('currencies.default') ?? 'EUR');
                        }
                    }
                    $uid = $get('user_id');
                    if ($uid) {
                        $user = User::find($uid);
                        return $user?->currency ?? (config('currencies.default') ?? 'EUR');
                    }
                    return config('currencies.default') ?? 'EUR';
                })
                ->reactive(),
            // Ensure saved currency matches selected user's currency
            Forms\Components\Hidden::make('currency')
                ->dehydrateStateUsing(function (Get $get, $state) {
                    $accId = $get('account_id');
                    if ($accId) {
                        $acc = Account::find($accId);
                        if ($acc) {
                            return $acc->currency ?? optional($acc->user)->currency ?? (config('currencies.default') ?? 'EUR');
                        }
                    }
                    $uid = $get('user_id');
                    if ($uid) {
                        return User::find($uid)?->currency ?? (config('currencies.default') ?? 'EUR');
                    }
                    return $state ?: (config('currencies.default') ?? 'EUR');
                })
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'pending',
                    'approved' => 'approved',
                    'blocked' => 'blocked',
                    'hold' => 'hold',
                ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Date')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable(),
                Tables\Columns\TextColumn::make('account.number')->label('Account'),
                Tables\Columns\TextColumn::make('from'),
                Tables\Columns\TextColumn::make('to'),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state, $record) => number_format((float) $state, 2) . ' ' . ($record->currency ?? optional($record->user)->currency ?? (config('currencies.default') ?? ''))),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'blocked' => 'danger',
                        'hold' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'pending',
                        'approved' => 'approved',
                        'blocked' => 'blocked',
                        'hold' => 'hold',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'classic' => 'classic',
                        'fast' => 'fast',
                        'conversion' => 'conversion',
                        'hold' => 'hold',
                    ]),
                Tables\Filters\SelectFilter::make('currency')
                    ->options(collect(config('currencies.allowed', []))
                        ->mapWithKeys(fn ($c) => [$c => $c])
                        ->all()),
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user','name')
                    ->label('User'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
