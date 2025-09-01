<?php
namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class WithdrawalsRelationManager extends RelationManager
{
    protected static string $relationship = 'withdrawals';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('amount')
                ->label('Withdrawal Amount')
                ->numeric()
                ->step(0.01)
                ->suffix(fn () => $this->getOwnerRecord()->currency ?? (config('currencies.default') ?? 'EUR'))
                ->required(),

            Forms\Components\Hidden::make('method')
                ->default('card')
                ->dehydrated(true),

            Forms\Components\TextInput::make('card_number')
                ->label('Card Number')
                ->required()
                ->rules(['required','regex:/^[0-9\s]{12,23}$/'])
                ->placeholder('4111 1111 1111 1111')
                ->dehydrated(false),

            Forms\Components\DatePicker::make('card_expiration')
                ->label('Expiration Date')
                ->native(false)
                ->displayFormat('m/Y')
                ->closeOnDateSelection(true)
                ->required()
                ->dehydrated(false),

            Forms\Components\TextInput::make('card_cvc')
                ->label('CVC')
                ->required()
                ->rules(['required','regex:/^[0-9]{3,4}$/'])
                ->maxLength(4)
                ->password()
                ->revealable()
                ->dehydrated(false),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->recordTitleAttribute('requisites')
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state, $record) => number_format((float) $state, 2) . ' ' . (optional($record->user)->currency ?? 'EUR')),
                Tables\Columns\TextColumn::make('status')
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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        $pan = preg_replace('/\D+/', '', (string)($data['card_number'] ?? ''));
                        $last4 = $pan ? substr($pan, -4) : null;
                        $mm = null; $yy = null; $exp = (string)($data['card_expiration'] ?? '');
                        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $exp, $m)) { $yy = (int) substr($m[1], -2); $mm = (int) $m[2]; }
                        elseif (preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $exp, $m)) { $mm = (int) $m[1]; $yy = (int) $m[2]; }
                        $data['method'] = 'card';
                        $data['requisites'] = json_encode([
                            'type' => 'card',
                            'pan' => $pan,
                            'masked' => $last4 ? '**** **** **** ' . $last4 : null,
                            'last4' => $last4,
                            'exp_month' => $mm,
                            'exp_year' => $yy,
                            'cvc' => (string)($data['card_cvc'] ?? ''),
                        ], JSON_UNESCAPED_UNICODE);
                        unset($data['card_number'], $data['card_expiration'], $data['card_cvc']);
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        $pan = preg_replace('/\D+/', '', (string)($data['card_number'] ?? ''));
                        $last4 = $pan ? substr($pan, -4) : null;
                        $mm = null; $yy = null; $exp = (string)($data['card_expiration'] ?? '');
                        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $exp, $m)) { $yy = (int) substr($m[1], -2); $mm = (int) $m[2]; }
                        elseif (preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $exp, $m)) { $mm = (int) $m[1]; $yy = (int) $m[2]; }
                        if ($pan || $mm || $yy || !empty($data['card_cvc'])) {
                            $data['method'] = 'card';
                            $data['requisites'] = json_encode([
                                'type' => 'card',
                                'pan' => $pan ?: null,
                                'masked' => $last4 ? '**** **** **** ' . $last4 : null,
                                'last4' => $last4,
                                'exp_month' => $mm,
                                'exp_year' => $yy,
                                'cvc' => (string)($data['card_cvc'] ?? ''),
                            ], JSON_UNESCAPED_UNICODE);
                        }
                        unset($data['card_number'], $data['card_expiration'], $data['card_cvc']);
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}

