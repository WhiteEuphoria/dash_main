<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AccountsRelationManager extends RelationManager
{
    protected static string $relationship = 'accounts';

    protected static ?string $title = 'Dashboard';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('number')
                ->label('Account number')
                ->required()
                ->default(fn () => \App\Support\AccountNumber::generate())
                ->rule(fn (?Model $record) => Rule::unique('accounts', 'number')->ignore($record))
                ->suffixAction(\Filament\Forms\Components\Actions\Action::make('generate')
                    ->icon('heroicon-o-sparkles')
                    ->label('Generate')
                    ->action(fn (\Filament\Forms\Set $set) => $set('number', \App\Support\AccountNumber::generate()))
                ),
            // Name removed per request; use Account Number only
            Forms\Components\Select::make('type')
                ->label('Type')
                ->options(config('accounts.types'))
                ->required(),
            Forms\Components\TextInput::make('beneficiary')
                ->label('Beneficiary')
                ->nullable(),
            Forms\Components\TextInput::make('organization')
                ->label('Organization')
                ->nullable(),
            Forms\Components\TextInput::make('investment_control')
                ->label('Investment Control')
                ->nullable(),
            Forms\Components\TextInput::make('balance')
                ->label('Balance')
                ->numeric()
                ->step(0.01)
                ->suffix(function (\Filament\Forms\Get $get, \Filament\Resources\RelationManagers\RelationManager $livewire) {
                    return optional($livewire->getOwnerRecord())->currency ?? (config('currencies.default') ?? 'EUR');
                })
                ->required(),
            Forms\Components\DatePicker::make('term')
                ->label('Expiration Date')
                ->native(false)
                ->required(),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'Pending' => 'Pending',
                    'Active' => 'Active',
                    'Hold' => 'Hold',
                    'Blocked' => 'Blocked',
                ])
                ->required(),
            Forms\Components\Toggle::make('is_default')
                ->label('Primary (show first)')
                ->default(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Account number'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => (config('accounts.types')[$state] ?? $state)),
                Tables\Columns\TextColumn::make('balance')->label('Balance')
                    ->formatStateUsing(fn ($state, $record) => number_format((float) $state, 2) . ' ' . ($record->currency ?? 'EUR')),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
