<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepRoleResource\Pages;
use App\Filament\Resources\DepRoleResource\RelationManagers;
use App\Models\DepRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepRoleResource extends Resource
{
    protected static ?string $model = DepRole::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Unit Management';

    protected static ?int $navigationSort = 8;

    protected static ?string $label = 'Department Roles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('department_type')
                    ->options([
                        'Core Ministries' => [
                            'preacher'     => 'Preaching & Teaching',
                            'worship'      => 'Worship & Music',
                            'prayer'       => 'Prayer & Intercession',
                            'discipleship' => 'Discipleship & Doctrine',
                        ],

                        'Pastoral & Care' => [
                            'pastoral_care' => 'Pastoral Care & Counseling',
                            'follow_up'     => 'Follow-Up & Assimilation',
                            'visitation'    => 'Visitation & Benevolence',
                        ],

                        'Outreach & Growth' => [
                            'evangelism' => 'Evangelism & Outreach',
                            'missions'   => 'Missions',
                        ],

                        'Age-Based Ministries' => [
                            'children'     => 'Children Ministry',
                            'youth'        => 'Youth Ministry',
                            'young_adults' => 'Young Adults Ministry',
                            'women'        => 'Women Ministry',
                            'men'          => 'Men Ministry',
                        ],

                        'Operations' => [
                            'administration' => 'Administration',
                            'finance'        => 'Finance',
                            'stewardship'     => 'Stewardship',
                        ],

                        'Support & Media' => [
                            'technical'   => 'Technical & Media',
                            'protocol'    => 'Protocol & Hospitality',
                            'security'    => 'Security & Safety',
                        ],
                    ])
                    ->required()
                    ->label('Department')
                    ->native(false),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('department_type')
                    ->colors([
                        'warning' => 'worship',
                        'danger' => 'technical',
                        'info' => 'preacher',
                    ]),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_type')
                    ->options([
                        'worship' => 'Worship',
                        'technical' => 'Technical',
                        'preacher' => 'Preacher',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
            ])
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
            'index' => Pages\ListDepRoles::route('/'),
            'create' => Pages\CreateDepRole::route('/create'),
            'edit' => Pages\EditDepRole::route('/{record}/edit'),
        ];
    }
}
