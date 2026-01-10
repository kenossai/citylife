<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationInterestResource\Pages;
use App\Filament\Resources\RegistrationInterestResource\RelationManagers;
use App\Models\RegistrationInterest;
use App\Notifications\RegistrationInvitation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Mail;

class RegistrationInterestResource extends Resource
{
    protected static ?string $model = RegistrationInterest::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Registration Interests';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('registered_at')
                    ->label('Registered')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Approved By')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\Filter::make('registered')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('registered_at'))
                    ->label('Completed Registration'),
                Tables\Filters\Filter::make('not_registered')
                    ->query(fn (Builder $query): Builder => $query->whereNull('registered_at'))
                    ->label('Not Registered Yet'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (RegistrationInterest $record): bool => $record->isPending())
                    ->action(function (RegistrationInterest $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);

                        // Generate token and send invitation email
                        $token = $record->generateToken();

                        // Send email notification
                        \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                            ->notify(new RegistrationInvitation($record));

                        Notification::make()
                            ->title('Interest Approved')
                            ->success()
                            ->body('Registration invitation has been sent to ' . $record->email)
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (RegistrationInterest $record): bool => $record->isPending())
                    ->action(function (RegistrationInterest $record) {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Interest Rejected')
                            ->warning()
                            ->send();
                    }),
                Tables\Actions\Action::make('resend')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->visible(fn (RegistrationInterest $record): bool => $record->isApproved() && !$record->isRegistered())
                    ->action(function (RegistrationInterest $record) {
                        if (!$record->token) {
                            $record->generateToken();
                        }

                        \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                            ->notify(new RegistrationInvitation($record));

                        Notification::make()
                            ->title('Invitation Resent')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->isPending()) {
                                    $record->update([
                                        'status' => 'approved',
                                        'approved_by' => auth()->id(),
                                        'approved_at' => now(),
                                    ]);

                                    $token = $record->generateToken();
                                    \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                                        ->notify(new RegistrationInvitation($record));
                                }
                            }

                            Notification::make()
                                ->title('Interests Approved')
                                ->success()
                                ->body('Registration invitations have been sent')
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListRegistrationInterests::route('/'),
            'create' => Pages\CreateRegistrationInterest::route('/create'),
            'edit' => Pages\EditRegistrationInterest::route('/{record}/edit'),
        ];
    }
}
