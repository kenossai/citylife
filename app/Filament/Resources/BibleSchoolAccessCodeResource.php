<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BibleSchoolAccessCodeResource\Pages;
use App\Filament\Resources\BibleSchoolAccessCodeResource\RelationManagers;
use App\Models\BibleSchoolAccessCode;
use App\Models\BibleSchoolEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BibleSchoolAccessCodeResource extends Resource
{
    protected static ?string $model = BibleSchoolAccessCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Bible School';

    protected static ?string $navigationLabel = 'Access Codes (Legacy)';

    /**
     * Hidden – access codes are now email-based OTP tokens (BibleSchoolOtpToken).
     */
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Code Information')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Access Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->default(fn () => BibleSchoolAccessCode::generateUniqueCode())
                            ->helperText('A unique code will be generated automatically'),
                        Forms\Components\Select::make('bible_school_event_id')
                            ->label('Event')
                            ->relationship('event', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Student Information')
                    ->schema([
                        Forms\Components\TextInput::make('student_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('student_email')
                            ->email()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->helperText('Leave empty for no expiration'),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Usage Statistics')
                    ->schema([
                        Forms\Components\Placeholder::make('usage_count')
                            ->content(fn (?BibleSchoolAccessCode $record) => $record?->usage_count ?? 0),
                        Forms\Components\Placeholder::make('last_used_at')
                            ->content(fn (?BibleSchoolAccessCode $record) =>
                                $record?->last_used_at?->format('M d, Y H:i') ?? 'Never used'
                            ),
                    ])
                    ->columns(2)
                    ->hidden(fn (?BibleSchoolAccessCode $record) => $record === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_count')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('last_used_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bible_school_event_id')
                    ->label('Event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Filters\Filter::make('expired')
                    ->query(fn (Builder $query) => $query->where('expires_at', '<', now()))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBibleSchoolAccessCodes::route('/'),
            'create' => Pages\CreateBibleSchoolAccessCode::route('/create'),
            'edit' => Pages\EditBibleSchoolAccessCode::route('/{record}/edit'),
        ];
    }
}
