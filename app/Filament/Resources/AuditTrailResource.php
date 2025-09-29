<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditTrailResource\Pages;
use App\Filament\Resources\AuditTrailResource\RelationManagers;
use App\Models\AuditTrail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuditTrailResource extends Resource
{
    protected static ?string $model = AuditTrail::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $navigationGroup = 'Security & Compliance';

    protected static ?string $navigationLabel = 'Audit Trail';

    protected static ?int $navigationSort = 1;

    // Audit trails are read-only - no create/edit forms needed
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Audit Details')
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Timestamp')
                            ->content(fn ($record) => $record->created_at->format('M j, Y \a\t g:i:s A')),

                        Forms\Components\Placeholder::make('user_display_name')
                            ->label('User')
                            ->content(fn ($record) => $record->user_display_name),

                        Forms\Components\Placeholder::make('action')
                            ->label('Action')
                            ->content(fn ($record) => AuditTrail::getActions()[$record->action] ?? $record->action),

                        Forms\Components\Placeholder::make('resource_type_name')
                            ->label('Resource Type')
                            ->content(fn ($record) => $record->resource_type_name),

                        Forms\Components\Placeholder::make('resource_name')
                            ->label('Resource')
                            ->content(fn ($record) => $record->resource_name ?? 'N/A'),

                        Forms\Components\Placeholder::make('ip_address')
                            ->label('IP Address')
                            ->content(fn ($record) => $record->ip_address),
                    ])->columns(2),

                Forms\Components\Section::make('Data Changes')
                    ->schema([
                        Forms\Components\Placeholder::make('old_values')
                            ->label('Previous Values')
                            ->content(fn ($record) => $record->old_values ?
                                view('filament.components.json-display', ['data' => $record->old_values])->render() :
                                'No previous data'
                            )
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('new_values')
                            ->label('New Values')
                            ->content(fn ($record) => $record->new_values ?
                                view('filament.components.json-display', ['data' => $record->new_values])->render() :
                                'No new data'
                            )
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Placeholder::make('description')
                            ->label('Description')
                            ->content(fn ($record) => $record->description ?? 'No description'),

                        Forms\Components\Placeholder::make('category')
                            ->label('Category')
                            ->content(fn ($record) => AuditTrail::getCategories()[$record->category] ?? $record->category),

                        Forms\Components\Placeholder::make('severity')
                            ->label('Severity')
                            ->content(fn ($record) => AuditTrail::getSeverityLevels()[$record->severity] ?? $record->severity),

                        Forms\Components\Placeholder::make('is_sensitive')
                            ->label('Sensitive Data')
                            ->content(fn ($record) => $record->is_sensitive ? 'Yes' : 'No'),

                        Forms\Components\Placeholder::make('user_agent')
                            ->label('User Agent')
                            ->content(fn ($record) => $record->user_agent ?? 'Unknown')
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('url')
                            ->label('URL')
                            ->content(fn ($record) => $record->url ?? 'Unknown')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Timestamp')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                Tables\Columns\TextColumn::make('user_display_name')
                    ->label('User')
                    ->searchable(['user_name', 'user_email'])
                    ->sortable('user_name'),

                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'create' => 'success',
                        'update' => 'warning',
                        'delete' => 'danger',
                        'view', 'access' => 'info',
                        'export', 'download' => 'gray',
                        'login' => 'success',
                        'logout' => 'gray',
                        default => 'primary'
                    })
                    ->formatStateUsing(fn ($state) => AuditTrail::getActions()[$state] ?? $state),

                Tables\Columns\TextColumn::make('resource_type_name')
                    ->label('Resource')
                    ->searchable(['resource_type'])
                    ->sortable('resource_type'),

                Tables\Columns\TextColumn::make('resource_name')
                    ->label('Item')
                    ->searchable()
                    ->limit(30)
                    ->placeholder('N/A'),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'sensitive' => 'danger',
                        'financial' => 'warning',
                        'personal' => 'info',
                        'authentication' => 'success',
                        'administration' => 'gray',
                        default => 'primary'
                    })
                    ->formatStateUsing(fn ($state) => AuditTrail::getCategories()[$state] ?? $state),

                Tables\Columns\TextColumn::make('severity')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        'critical' => 'danger'
                    })
                    ->formatStateUsing(fn ($state) => AuditTrail::getSeverityLevels()[$state] ?? $state),

                Tables\Columns\IconColumn::make('is_sensitive')
                    ->label('Sensitive')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('danger'),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->fontFamily('mono')
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options(AuditTrail::getActions())
                    ->multiple(),

                Tables\Filters\SelectFilter::make('category')
                    ->options(AuditTrail::getCategories())
                    ->multiple(),

                Tables\Filters\SelectFilter::make('severity')
                    ->options(AuditTrail::getSeverityLevels())
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_sensitive')
                    ->label('Sensitive Data')
                    ->placeholder('All records')
                    ->trueLabel('Sensitive only')
                    ->falseLabel('Non-sensitive only'),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View Details')
                    ->icon('heroicon-m-eye')
                    ->color('info')
                    ->modalHeading('Audit Trail Details')
                    ->modalContent(fn ($record) => view('filament.audit-trail-details', compact('record')))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->bulkActions([
                // Audit trails should not be deleted
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // Auto-refresh every 30 seconds
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
            'index' => Pages\ListAuditTrails::route('/'),
            // No create or edit pages - audit trails are read-only
        ];
    }

    // Only allow viewing audit trails, not creating/editing
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
