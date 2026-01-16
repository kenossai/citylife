<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffUserResource\Pages;
use App\Filament\Resources\StaffUserResource\RelationManagers;
use App\Models\User;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class StaffUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Admin Users';

    protected static ?int $navigationSort = 3;

    protected static string $policy = \App\Policies\UserPolicy::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Full name for display'),

                        Forms\Components\TextInput::make('first_name')
                            ->maxLength(255)
                            ->placeholder('First name'),

                        Forms\Components\TextInput::make('last_name')
                            ->maxLength(255)
                            ->placeholder('Last name'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('+44 123 456 7890'),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->placeholder('Leave empty to keep current password'),
                    ])->columns(2),

                Forms\Components\Section::make('Employment Details')
                    ->schema([
                        Forms\Components\TextInput::make('job_title')
                            ->maxLength(255)
                            ->placeholder('e.g., Pastor, Administrator'),

                        Forms\Components\TextInput::make('department')
                            ->maxLength(255)
                            ->placeholder('e.g., Pastoral Care, Administration'),

                        Forms\Components\DatePicker::make('hire_date')
                            ->native(false),

                        Forms\Components\Select::make('employment_status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                                'terminated' => 'Terminated',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Account Active')
                            ->default(true)
                            ->helperText('Inactive accounts cannot log in'),

                        Forms\Components\Toggle::make('force_password_change')
                            ->label('Force Password Change')
                            ->helperText('User must change password on next login'),
                    ])->columns(2),

                Forms\Components\Section::make('Role Assignment')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'display_name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->optionsLimit(50)
                            ->helperText('Assign one or more roles to this user')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('bio')
                            ->maxLength(1000)
                            ->placeholder('Brief biography or description')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('avatar')
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->disk('s3')
                            ->visibility('public')
                            ->directory('avatars')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Login Information')
                    ->schema([
                        Forms\Components\Placeholder::make('last_login_at')
                            ->label('Last Login')
                            ->content(fn ($record) => $record?->last_login_at ?
                                $record->last_login_at->format('M j, Y \a\t g:i A') . ' (' . $record->last_login_at->diffForHumans() . ')' :
                                'Never logged in'
                            ),

                        Forms\Components\Placeholder::make('last_login_ip')
                            ->label('Last Login IP')
                            ->content(fn ($record) => $record?->last_login_ip ?? 'Unknown'),
                    ])
                    ->columns(2)
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->disk('s3')
                    ->visibility('public')
                    ->size(60),

                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\TextColumn::make('job_title')
                    ->searchable()
                    ->placeholder('No title set'),

                Tables\Columns\TextColumn::make('department')
                    ->searchable()
                    ->placeholder('No department'),

                Tables\Columns\TextColumn::make('roles.display_name')
                    ->badge()
                    ->separator(', ')
                    ->color(fn ($record, $state) => $record->roles->first()?->color ?? 'gray')
                    ->limit(2),

                Tables\Columns\BadgeColumn::make('employment_status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => 'suspended',
                        'gray' => 'terminated',
                    ]),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Login')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Last Login')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Never logged in')
                    ->since()
                    ->description(fn ($record) => $record->last_login_at ?
                        'IP: ' . ($record->last_login_ip ?? 'Unknown') : null
                    ),

                Tables\Columns\TextColumn::make('hire_date')
                    ->date()
                    ->sortable()
                    ->placeholder('Not set')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('employment_status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'terminated' => 'Terminated',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Login Status')
                    ->boolean()
                    ->trueLabel('Can login')
                    ->falseLabel('Cannot login')
                    ->native(false),

                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'display_name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\Filter::make('recent_login')
                    ->query(fn (Builder $query): Builder => $query->where('last_login_at', '>=', now()->subDays(30)))
                    ->label('Active in last 30 days'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('impersonate')
                        ->icon('heroicon-m-user-circle')
                        ->color('warning')
                        ->action(function (User $record) {
                            // Add impersonation logic here if needed
                            session()->flash('message', "Impersonating {$record->name}");
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Impersonate User')
                        ->modalDescription('Are you sure you want to log in as this user?')
                        ->visible(fn () => \Illuminate\Support\Facades\Auth::user()?->hasPermission('system.manage_users')),
                    Tables\Actions\DeleteAction::make()
                        ->before(function (Tables\Actions\DeleteAction $action, User $record) {
                            if ($record->hasRole('super_admin') && User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->count() === 1) {
                                $action->cancel();
                                $action->sendFailureNotification('Cannot delete the last super admin user.');
                            }
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Users activated successfully'),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->icon('heroicon-m-x-circle')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Users deactivated successfully'),

                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Tables\Actions\DeleteBulkAction $action, $records) {
                            $superAdmins = $records->filter(fn ($record) => $record->hasRole('super_admin'));
                            if ($superAdmins->isNotEmpty()) {
                                $totalSuperAdmins = User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->count();
                                if ($totalSuperAdmins - $superAdmins->count() < 1) {
                                    $action->cancel();
                                    $action->sendFailureNotification('Cannot delete all super admin users.');
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListStaffUsers::route('/'),
            'create' => Pages\CreateStaffUser::route('/create'),
            'edit' => Pages\EditStaffUser::route('/{record}/edit'),
        ];
    }
}
