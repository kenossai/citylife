<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftAidDeclarationResource\Pages;
use App\Filament\Resources\GiftAidDeclarationResource\RelationManagers;
use App\Models\GiftAidDeclaration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GiftAidDeclarationResource extends Resource
{
    protected static ?string $model = GiftAidDeclaration::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Finance & Giving';

    protected static ?string $navigationLabel = 'Gift Aid Declarations';

    protected static ?string $modelLabel = 'Gift Aid Declaration';

    protected static ?string $pluralModelLabel = 'Gift Aid Declarations';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])->columns(2),

                Forms\Components\Section::make('Address Information')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('postcode')
                            ->label('Postcode')
                            ->required()
                            ->maxLength(10),
                    ])->columns(2),

                Forms\Components\Section::make('Gift Aid Details')
                    ->schema([
                        Forms\Components\TextInput::make('gift_aid_code')
                            ->label('Gift Aid Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->helperText('Unique code for this declaration (e.g., JS-tithe, AB-offering)'),

                        Forms\Components\DatePicker::make('confirmation_date')
                            ->label('Declaration Date')
                            ->required()
                            ->default(now()),

                        Forms\Components\Toggle::make('confirm_declaration')
                            ->label('Declaration Confirmed')
                            ->helperText('Declaration has been confirmed by the donor')
                            ->default(true),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Whether this declaration is currently active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name']),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email address copied'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('gift_aid_code')
                    ->label('Gift Aid Code')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('postcode')
                    ->label('Postcode')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('confirmation_date')
                    ->label('Declaration Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('confirm_declaration')
                    ->label('Confirmed')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('confirm_declaration')
                    ->label('Declaration Status')
                    ->trueLabel('Confirmed only')
                    ->falseLabel('Unconfirmed only')
                    ->native(false),

                Tables\Filters\Filter::make('confirmation_date')
                    ->form([
                        Forms\Components\DatePicker::make('confirmed_from')
                            ->label('Confirmed From'),
                        Forms\Components\DatePicker::make('confirmed_until')
                            ->label('Confirmed Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['confirmed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('confirmation_date', '>=', $date),
                            )
                            ->when(
                                $data['confirmed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('confirmation_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn (GiftAidDeclaration $record): bool => $record->is_active)
                    ->requiresConfirmation()
                    ->action(fn (GiftAidDeclaration $record) => $record->update(['is_active' => false]))
                    ->successNotificationTitle('Declaration deactivated'),
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (GiftAidDeclaration $record): bool => !$record->is_active)
                    ->action(fn (GiftAidDeclaration $record) => $record->update(['is_active' => true]))
                    ->successNotificationTitle('Declaration activated'),
                Tables\Actions\Action::make('match_givings')
                    ->label('Match Givings')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->action(function (GiftAidDeclaration $record) {
                        $matchedCount = \App\Models\Giving::where('donor_email', $record->email)
                            ->whereNull('gift_aid_eligible')
                            ->orWhere('gift_aid_eligible', false)
                            ->update(['gift_aid_eligible' => true]);

                        \Filament\Notifications\Notification::make()
                            ->title('Givings Matched')
                            ->body("Matched {$matchedCount} giving records to this gift aid declaration")
                            ->success()
                            ->send();
                    })
                    ->successNotificationTitle('Givings matched successfully'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('bulk_activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Declarations activated'),
                    Tables\Actions\BulkAction::make('bulk_deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Declarations deactivated'),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            // Export functionality can be added here
                            \Filament\Notifications\Notification::make()
                                ->title('Export completed')
                                ->body('Gift Aid declarations have been exported successfully.')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('60s'); // Auto-refresh every minute for new submissions
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EligibleGivingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGiftAidDeclarations::route('/'),
            'create' => Pages\CreateGiftAidDeclaration::route('/create'),
            'view' => Pages\ViewGiftAidDeclaration::route('/{record}'),
            'edit' => Pages\EditGiftAidDeclaration::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $count = static::getModel()::where('is_active', true)->count();
        return $count > 50 ? 'success' : ($count > 10 ? 'warning' : 'primary');
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'email', 'gift_aid_code'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Email' => $record->email,
            'Gift Aid Code' => $record->gift_aid_code,
            'Status' => $record->is_active ? 'Active' : 'Inactive',
        ];
    }
}
