<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BibleSchoolOtpTokenResource\Pages;
use App\Models\BibleSchoolOtpToken;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BibleSchoolOtpTokenResource extends Resource
{
    protected static ?string $model = BibleSchoolOtpToken::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationGroup = 'Bible School';

    protected static ?string $navigationLabel = 'Access Requests';

    protected static ?string $modelLabel = 'Access Request';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Token Details')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->disabled(),
                        Forms\Components\TextInput::make('code')
                            ->disabled(),
                        Forms\Components\TextInput::make('year')
                            ->disabled(),
                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('used_at')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->disabled(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('year')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('speaker.name')
                    ->label('Speaker')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('used_at')
                    ->label('Used')
                    ->boolean()
                    ->getStateUsing(fn (BibleSchoolOtpToken $record) => ! is_null($record->used_at))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('used_at')
                    ->label('Used At')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not used'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable()
                    ->color(fn (BibleSchoolOtpToken $record) =>
                        $record->expires_at->isPast() && is_null($record->used_at) ? 'danger' : null
                    ),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->options(fn () =>
                        BibleSchoolOtpToken::distinct()->pluck('year', 'year')->sort()->reverse()->toArray()
                    ),
                Tables\Filters\TernaryFilter::make('used')
                    ->label('Used')
                    ->nullable()
                    ->attribute('used_at'),
                Tables\Filters\Filter::make('expired')
                    ->label('Expired (unused)')
                    ->query(fn (Builder $q) =>
                        $q->whereNull('used_at')->where('expires_at', '<', now())
                    )
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false; // tokens are auto-generated, not manually created
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBibleSchoolOtpTokens::route('/'),
            'view'  => Pages\ViewBibleSchoolOtpToken::route('/{record}'),
        ];
    }
}
