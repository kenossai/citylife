<?php

namespace App\Filament\Resources\GiftAidDeclarationResource\RelationManagers;

use App\Models\Giving;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EligibleGivingsRelationManager extends RelationManager
{
    protected static string $relationship = 'eligibleGivings';

    protected static ?string $title = 'Gift Aid Eligible Givings';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->modifyQueryUsing(function (Builder $query) {
                $record = $this->getOwnerRecord();
                return $query->where('gift_aid_eligible', true)
                    ->where(function ($subQuery) use ($record) {
                        $subQuery->where('donor_email', $record->email)
                                 ->orWhere('donor_name', $record->full_name);
                    });
            })
            ->columns([
                Tables\Columns\TextColumn::make('given_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('GBP')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('giving_type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'tithe',
                        'success' => 'offering',
                        'warning' => 'special_offering',
                        'info' => 'missions',
                        'danger' => 'building_fund',
                        'secondary' => 'other',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tithe' => 'Tithe',
                        'offering' => 'Offering',
                        'special_offering' => 'Special Offering',
                        'missions' => 'Missions',
                        'building_fund' => 'Building Fund',
                        'youth_ministry' => 'Youth Ministry',
                        'other' => 'Other',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'bank_transfer' => 'Bank Transfer',
                        'online' => 'Online',
                        'card' => 'Card',
                        'cash' => 'Cash',
                        'cheque' => 'Cheque',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Reference')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('giving_type')
                    ->options([
                        'tithe' => 'Tithe',
                        'offering' => 'Offering',
                        'special_offering' => 'Special Offering',
                        'missions' => 'Missions',
                        'building_fund' => 'Building Fund',
                        'youth_ministry' => 'Youth Ministry',
                        'other' => 'Other',
                    ]),
                Tables\Filters\Filter::make('given_date')
                    ->form([
                        Forms\Components\DatePicker::make('given_from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('given_until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['given_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('given_date', '>=', $date),
                            )
                            ->when(
                                $data['given_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('given_date', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(fn (): string => route('filament.admin.resources.givings.create', [
                        'donor_email' => $this->getOwnerRecord()->email,
                        'donor_name' => $this->getOwnerRecord()->full_name,
                        'gift_aid_eligible' => true,
                    ])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Giving $record): string => route('filament.admin.resources.givings.view', $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('calculate_gift_aid')
                        ->label('Calculate Gift Aid')
                        ->icon('heroicon-o-calculator')
                        ->action(function ($records) {
                            $totalAmount = $records->sum('amount');
                            $giftAidAmount = $totalAmount * 0.25; // 25% Gift Aid

                            \Filament\Notifications\Notification::make()
                                ->title('Gift Aid Calculation')
                                ->body("Total eligible: £{$totalAmount} | Gift Aid claimable: £{$giftAidAmount}")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('given_date', 'desc');
    }
}
