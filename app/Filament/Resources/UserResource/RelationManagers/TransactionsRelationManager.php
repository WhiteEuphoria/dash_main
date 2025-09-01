<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $recordTitleAttribute = 'created_at';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DateTimePicker::make('created_at')
                ->label('Date & Time')
                ->required()
                ->native(false),

            Forms\Components\Select::make('account_id')
                ->label('Account')
                ->options(fn () => Account::where('user_id', $this->getOwnerRecord()->id)->pluck('number', 'id'))
                ->searchable()
                ->preload()
                ->nullable(),

            Forms\Components\TextInput::make('from')
                ->label('From')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('to')
                ->label('To')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('type')
                ->label('Type')
                ->options([
                    'classic' => 'classic',
                    'fast' => 'fast',
                    'conversion' => 'conversion',
                    'hold' => 'hold',
                ])
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->label('Amount')
                ->numeric()
                ->rules(['numeric', 'gte:0.01'])
                ->suffix($this->getOwnerRecord()->currency)
                ->reactive()
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'pending',
                    'approved' => 'approved',
                    'blocked' => 'blocked',
                    'hold' => 'hold',
                ])
                ->required(),

            Forms\Components\Hidden::make('currency')
                ->dehydrateStateUsing(function () {
                    return $this->getOwnerRecord()->currency ?? (config('currencies.default') ?? 'EUR');
                })
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime()->sortable(),
            Tables\Columns\TextColumn::make('account.number')->label('Account'),
            Tables\Columns\TextColumn::make('from')->label('From'),
            Tables\Columns\TextColumn::make('to')->label('To'),
            Tables\Columns\TextColumn::make('type')->label('Type')->badge(),
            Tables\Columns\TextColumn::make('amount')
                ->label('Amount')
                ->formatStateUsing(fn ($state, $record) => number_format((float) $state, 2) . ' ' . ($record->currency ?? 'EUR')),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    'blocked' => 'danger',
                    'hold' => 'gray',
                    default => 'gray',
                }),
        ])
        ->defaultSort('created_at', 'desc')
        ->headerActions([])
        ->actions([])
        ->bulkActions([]);
    }
}
