<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages;
use App\Filament\Resources\TeamMemberResource\RelationManagers;
use App\Models\TeamMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Leadership Members';
    protected static ?string $modelLabel = 'Team Member';
    protected static ?string $navigationGroup = 'Leadership Team';
    protected static ?int $navigationGroupSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Information')->schema([
                Forms\Components\TextInput::make('title')->placeholder('Dr., Rev., Pastor, etc.'),
                Forms\Components\TextInput::make('first_name')->required(),
                Forms\Components\TextInput::make('last_name')->required(),
                Forms\Components\TextInput::make('position')->required()->placeholder('Senior Pastor, Assistant Pastor, etc.'),
                Forms\Components\Select::make('team_type')->required()->options([
                    'pastoral' => 'Pastoral Team',
                    'leadership' => 'Leadership Team',
                ])->default('leadership'),
            ])->columns(2),

            Forms\Components\Section::make('Contact Information')->schema([
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\Toggle::make('show_contact_info')->label('Show Contact Info Publicly'),
            ])->columns(3),

            Forms\Components\Section::make('Biography & Description')->schema([
                Forms\Components\RichEditor::make('bio')->columnSpanFull()->toolbarButtons([
                    'bold', 'italic', 'underline', 'strike', 'link', 'bulletList', 'orderedList', 'blockquote'
                ]),
                Forms\Components\Textarea::make('short_description')->maxLength(500)->rows(3),
                Forms\Components\Textarea::make('ministry_focus')->placeholder('What they focus on in ministry')->rows(3),
            ])->columns(2),

            Forms\Components\Section::make('Responsibilities & Ministry Areas')->schema([
                Forms\Components\TagsInput::make('responsibilities')->placeholder('Add responsibilities...'),
                Forms\Components\TagsInput::make('ministry_areas')->placeholder('Areas they serve in...'),
            ])->columns(2),

            Forms\Components\Section::make('Personal Information')->schema([
                Forms\Components\TextInput::make('spouse_name')->placeholder('Spouse name'),
                Forms\Components\TextInput::make('joined_church')->numeric()->placeholder('Year joined church')->minValue(1900)->maxValue(date('Y')),
                Forms\Components\TextInput::make('started_ministry')->numeric()->placeholder('Year started ministry')->minValue(1900)->maxValue(date('Y')),
            ])->columns(3),

            Forms\Components\Section::make('Ministry Details')->schema([
                Forms\Components\Textarea::make('calling_testimony')->placeholder('Their calling story')->rows(4),
                Forms\Components\Textarea::make('achievements')->placeholder('Notable achievements')->rows(3),
                Forms\Components\TagsInput::make('courses_taught')->placeholder('Courses they teach...'),
            ])->columns(1),

            Forms\Components\Section::make('Media')->schema([
                Forms\Components\FileUpload::make('profile_image')->image()->disk('s3')->visibility('public')->directory('team-members')
                    ->imageResizeMode('cover')->imageCropAspectRatio('1:1'),
                Forms\Components\FileUpload::make('featured_image')->image()->disk('s3')->visibility('public')->directory('team-members')
                    ->imageResizeMode('cover')->imageCropAspectRatio('16:9'),
            ])->columns(2),

            Forms\Components\Section::make('SEO & Settings')->schema([
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)
                    ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                        if (!$state && $record) {
                            $component->state(\Illuminate\Support\Str::slug($record->first_name . '-' . $record->last_name));
                        }
                    }),
                Forms\Components\TextInput::make('meta_title')->maxLength(60),
                Forms\Components\Textarea::make('meta_description')->maxLength(160)->rows(2),
                Forms\Components\TagsInput::make('meta_keywords')->placeholder('SEO keywords...'),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->default(true),
                Forms\Components\Toggle::make('is_featured')->default(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('profile_image')
            ->disk('s3')
            ->visibility('public')
            ->circular()->size(50),
            Tables\Columns\TextColumn::make('full_name')->label('Name')->searchable(['first_name', 'last_name'])->weight('bold'),
            Tables\Columns\TextColumn::make('position')->searchable()->wrap(),
            Tables\Columns\TextColumn::make('team_type')->badge()->color(fn (string $state): string => match ($state) {
                'pastoral' => 'success', 'leadership' => 'primary',
            })->formatStateUsing(fn (string $state): string => ucfirst($state) . ' Team'),
            Tables\Columns\TextColumn::make('books_count')
                ->label('Books')
                ->counts('books')
                ->badge()
                ->color('info')
                ->sortable(),
            Tables\Columns\TextColumn::make('joined_church')->label('Joined')->date('Y')->sortable(),
            Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable(),
            Tables\Columns\IconColumn::make('is_active')->boolean()->label('Active'),
            Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Featured'),
        ])->filters([
            Tables\Filters\SelectFilter::make('team_type')->options([
                'pastoral' => 'Pastoral Team',
                'leadership' => 'Leadership Team',
            ]),
            Tables\Filters\TernaryFilter::make('is_active')->label('Active Status'),
            Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
        ])->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])->defaultSort('sort_order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BooksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'view' => Pages\ViewTeamMember::route('/{record}'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}
