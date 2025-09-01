<?php
namespace App\Filament\Resources;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User; use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash; use Filament\Pages\Page; use Illuminate\Database\Eloquent\Model;
class UserResource extends Resource {
    protected static ?string $model = User::class; protected static ?string $navigationIcon = 'heroicon-o-users';
    public static function form(Form $form): Form {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('password')->password()->dehydrateStateUsing(fn (string $state): string => Hash::make($state))->dehydrated(fn (?string $state): bool => filled($state))->required(fn (Page $livewire): bool => $livewire instanceof Pages\CreateUser),
            Forms\Components\Select::make('currency')
                ->label('Currency')
                ->options(collect(config('currencies.allowed', []))->mapWithKeys(fn ($c) => [$c => $c])->all())
                ->default(config('currencies.default'))
                ->live()
                ->required(),
            Forms\Components\TextInput::make('main_balance')
                ->numeric()
                ->step(0.01)
                ->suffix(fn ($get) => $get('currency') ?: config('currencies.default'))
                ->reactive()
                ->required(),
            Forms\Components\Select::make('verification_status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'active' => 'Active', // treat as approved
                    'rejected' => 'Rejected',
                ])
                ->required(),
            Forms\Components\Toggle::make('is_admin')->required(),
        ]);
    }
    public static function table(Table $table): Table {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(), Tables\Columns\TextColumn::make('email')->searchable(),
            Tables\Columns\IconColumn::make('is_admin')->boolean(),
            Tables\Columns\TextColumn::make('main_balance')->label('Balance')->sortable()
                ->formatStateUsing(fn ($state, $record) => number_format((float) $state, 2) . ' ' . ($record->currency ?? 'EUR')),
            Tables\Columns\TextColumn::make('verification_status')
                ->badge()
                ->color(fn (string $state): string => match (strtolower($state)) {
                    'pending' => 'warning',
                    'approved', 'active' => 'success',
                    'rejected' => 'danger',
                    default => 'gray',
                }),
        ])->actions([ Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()->visible(fn (Model $record): bool => $record->id !== 1), ]);
    }
    public static function getRelations(): array {
        return [
            RelationManagers\AccountsRelationManager::class,
            RelationManagers\FraudClaimsRelationManager::class,
            RelationManagers\WithdrawalsRelationManager::class,
            RelationManagers\DocumentsRelationManager::class,
            RelationManagers\TransactionsRelationManager::class,
        ];
    }
    public static function getPages(): array { return [ 'index' => Pages\ListUsers::route('/'), 'create' => Pages\CreateUser::route('/create'), 'edit' => Pages\EditUser::route('/{record}/edit'), ]; }
}
