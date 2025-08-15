<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicalDepartmentResource\Pages;
use App\Filament\Resources\TechnicalDepartmentResource\RelationManagers;
use App\Models\TechnicalDepartment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TechnicalDepartmentResource extends Resource
{
    protected static ?string $model = TechnicalDepartment::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Technical Ministry';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Department Information')
                    ->schema([
                        Forms\Components\Select::make('name')
                            ->required()
                            ->options([
                                'PA' => 'PA (Public Address)',
                                'Media' => 'Media',
                                'Visual' => 'Visual',
                            ])
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->helperText('URL-friendly version of the name'),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->helperText('Description of this department'),

                        Forms\Components\TextInput::make('head_of_department')
                            ->maxLength(255)
                            ->helperText('Name of the department head'),

                        Forms\Components\FileUpload::make('head_image')
                            ->image()
                            ->directory('technical-departments/heads')
                            ->helperText('Photo of the department head'),

                        Forms\Components\TextInput::make('contact_email')
                            ->email()
                            ->maxLength(255)
                            ->helperText('Contact email for this department'),

                        Forms\Components\Textarea::make('requirements')
                            ->rows(3)
                            ->helperText('Requirements to join this department'),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Order for display (lower numbers appear first)'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Whether this department is active'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('head_image')
                    ->label('Head Image')
                    ->circular()
                    ->defaultImageUrl(url('/assets/images/default-profile.jpg')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PA' => 'primary',
                        'Media' => 'success',
                        'Visual' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('head_of_department')
                    ->label('Department Head')
                    ->searchable()
                    ->placeholder('No head assigned'),

                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Contact Email')
                    ->searchable()
                    ->placeholder('No email provided'),

                Tables\Columns\TextColumn::make('activeMembers')
                    ->label('Active Members')
                    ->getStateUsing(fn (TechnicalDepartment $record): int => $record->activeMembers()->count())
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('name')
                    ->label('Department')
                    ->options([
                        'PA' => 'PA (Public Address)',
                        'Media' => 'Media',
                        'Visual' => 'Visual',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active departments')
                    ->falseLabel('Inactive departments')
                    ->native(false),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListTechnicalDepartments::route('/'),
            'create' => Pages\CreateTechnicalDepartment::route('/create'),
            'edit' => Pages\EditTechnicalDepartment::route('/{record}/edit'),
        ];
    }
}
