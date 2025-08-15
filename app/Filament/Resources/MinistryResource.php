<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MinistryResource\Pages;
use App\Filament\Resources\MinistryResource\RelationManagers;
use App\Models\Ministry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MinistryResource extends Resource
{
    protected static ?string $model = Ministry::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Member Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->helperText('URL-friendly version of the name'),
                        
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->helperText('Brief description for listings'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Whether this ministry is active and visible'),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Order for display (lower numbers appear first)'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Full content for the ministry page'),
                    ]),
                
                Forms\Components\Section::make('Leadership & Contact')
                    ->schema([
                        Forms\Components\TextInput::make('leader')
                            ->maxLength(255)
                            ->helperText('Name of the ministry leader'),
                        
                        Forms\Components\TextInput::make('contact_email')
                            ->email()
                            ->maxLength(255)
                            ->helperText('Contact email for inquiries'),
                        
                        Forms\Components\TextInput::make('meeting_time')
                            ->maxLength(255)
                            ->helperText('When the ministry meets (e.g., "Sundays 9:00 AM")'),
                        
                        Forms\Components\TextInput::make('meeting_location')
                            ->maxLength(255)
                            ->helperText('Where the ministry meets'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->image()
                            ->directory('ministries')
                            ->helperText('Featured image for the ministry'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl(url('/assets/images/default-ministry.jpg')),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('leader')
                    ->searchable()
                    ->sortable()
                    ->placeholder('No leader assigned'),
                
                Tables\Columns\TextColumn::make('meeting_time')
                    ->label('Meeting Time')
                    ->placeholder('Not specified'),
                
                Tables\Columns\TextColumn::make('activeMembers')
                    ->label('Active Members')
                    ->getStateUsing(fn (Ministry $record): int => $record->activeMembers()->count())
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active ministries')
                    ->falseLabel('Inactive ministries')
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
            'index' => Pages\ListMinistries::route('/'),
            'create' => Pages\CreateMinistry::route('/create'),
            'edit' => Pages\EditMinistry::route('/{record}/edit'),
        ];
    }
}
