<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BackupLogResource\Pages;
use App\Models\BackupLog;
use App\Services\BackupService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class BackupLogResource extends Resource
{
    protected static ?string $model = BackupLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Backup Logs';

    protected static ?string $modelLabel = 'Backup Log';

    protected static ?string $pluralModelLabel = 'Backup Logs';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Backup Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'full' => 'Full Backup',
                                'database' => 'Database Only',
                                'files' => 'Files Only',
                            ]),

                        Forms\Components\Select::make('trigger_type')
                            ->options([
                                'manual' => 'Manual',
                                'scheduled' => 'Scheduled',
                                'automatic' => 'Automatic',
                            ])
                            ->default('manual'),

                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\KeyValue::make('config')
                            ->label('Backup Configuration')
                            ->keyLabel('Setting')
                            ->valueLabel('Value'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'full',
                        'success' => 'database',
                        'warning' => 'files',
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'warning' => 'running',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),

                Tables\Columns\TextColumn::make('file_size_formatted')
                    ->label('File Size')
                    ->sortable('file_size'),

                Tables\Columns\TextColumn::make('duration_formatted')
                    ->label('Duration'),

                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                Tables\Columns\IconColumn::make('is_restorable')
                    ->boolean()
                    ->label('Restorable'),

                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->color(function ($record) {
                        return $record->expires_at && $record->expires_at->isPast() ? 'danger' : 'success';
                    }),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->default('System'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'full' => 'Full Backup',
                        'database' => 'Database Only',
                        'files' => 'Files Only',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'running' => 'Running',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),

                Tables\Filters\Filter::make('restorable')
                    ->query(fn (Builder $query): Builder => $query->where('is_restorable', true))
                    ->label('Restorable Only'),

                Tables\Filters\Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '<', now()))
                    ->label('Expired'),

                Tables\Filters\Filter::make('recent')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(7)))
                    ->label('Last 7 Days'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('download')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->action(function (BackupLog $record) {
                        if (!$record->file_path || !Storage::exists($record->file_path)) {
                            Notification::make()
                                ->title('Backup file not found')
                                ->danger()
                                ->send();
                            return;
                        }

                        return Storage::download($record->file_path, $record->name . '.zip');
                    })
                    ->visible(fn (BackupLog $record) => $record->status === 'completed' && $record->file_path),

                Tables\Actions\DeleteAction::make()
                    ->action(function (BackupLog $record) {
                        $backupService = app(BackupService::class);

                        if ($backupService->deleteBackup($record)) {
                            Notification::make()
                                ->title('Backup deleted successfully')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Failed to delete backup')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('cleanup_expired')
                    ->label('Delete Expired')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->action(function ($records) {
                        $backupService = app(BackupService::class);
                        $deletedCount = 0;

                        foreach ($records as $record) {
                            if ($record->expires_at && $record->expires_at->isPast()) {
                                if ($backupService->deleteBackup($record)) {
                                    $deletedCount++;
                                }
                            }
                        }

                        Notification::make()
                            ->title("Deleted {$deletedCount} expired backups")
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create_backup')
                    ->label('Create Backup')
                    ->icon('heroicon-m-plus')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->label('Backup Type')
                            ->required()
                            ->options([
                                'full' => 'Full Backup (Database + Files)',
                                'database' => 'Database Only',
                                'files' => 'Files Only',
                            ])
                            ->default('database'),

                        Forms\Components\TextInput::make('name')
                            ->label('Backup Name (Optional)')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->maxLength(65535),
                    ])
                    ->action(function (array $data) {
                        $backupService = app(BackupService::class);

                        try {
                            $options = [
                                'trigger_type' => 'manual',
                                'notes' => $data['notes'] ?? null,
                            ];

                            if (!empty($data['name'])) {
                                $options['name'] = $data['name'];
                            }

                            switch ($data['type']) {
                                case 'full':
                                    $backupService->createFullBackup($options);
                                    break;
                                case 'database':
                                    $backupService->createDatabaseBackup($options);
                                    break;
                                case 'files':
                                    $backupService->createFilesBackup($options);
                                    break;
                            }

                            Notification::make()
                                ->title('Backup initiated successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Backup failed: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('cleanup')
                    ->label('Cleanup Expired')
                    ->icon('heroicon-m-trash')
                    ->color('warning')
                    ->action(function () {
                        $backupService = app(BackupService::class);
                        $deletedCount = $backupService->cleanupExpiredBackups();

                        Notification::make()
                            ->title("Cleaned up {$deletedCount} expired backups")
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListBackupLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Backups should be created through the service, not manually
    }

    public static function canEdit($record): bool
    {
        return $record->status === 'pending'; // Only allow editing pending backups
    }
}
