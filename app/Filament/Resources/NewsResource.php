<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Article Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Textarea::make('excerpt')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Brief summary that will appear in news listings'),

                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Author')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Featured Image')
                            ->image()
                            ->directory('news')
                            ->imageEditor(),

                        Forms\Components\TextInput::make('author')
                            ->maxLength(255)
                            ->placeholder('e.g., Pastor John Smith'),
                    ])->columns(2),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(false),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Article')
                            ->helperText('Featured articles will be highlighted'),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date & Time')
                            ->default(now())
                            ->native(false),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular()
                    ->size(60),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),

                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\BadgeColumn::make('is_featured')
                    ->label('Featured')
                    ->colors([
                        'success' => true,
                        'secondary' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),

                Tables\Columns\BadgeColumn::make('is_published')
                    ->label('Status')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Draft'),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published Status')
                    ->placeholder('All articles')
                    ->trueLabel('Published only')
                    ->falseLabel('Drafts only'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status')
                    ->placeholder('All articles')
                    ->trueLabel('Featured only')
                    ->falseLabel('Regular only'),
            ])
            ->actions([
                Tables\Actions\Action::make('social_media_post')
                    ->label('Post to Social Media')
                    ->icon('heroicon-o-share')
                    ->color('info')
                    ->form([
                        Forms\Components\CheckboxList::make('platforms')
                            ->label('Select Platforms')
                            ->options([
                                'facebook' => 'Facebook',
                                'twitter' => 'Twitter/X',
                                'instagram' => 'Instagram',
                                'linkedin' => 'LinkedIn',
                            ])
                            ->required()
                            ->columns(2),
                    ])
                    ->action(function (array $data, News $record): void {
                        $service = new \App\Services\SocialMediaService();
                        $results = $service->postAnnouncement($record, $data['platforms']);

                        $successful = array_filter($results, fn($r) => $r['success']);
                        $failed = array_filter($results, fn($r) => !$r['success']);

                        if (count($successful) > 0) {
                            \Filament\Notifications\Notification::make()
                                ->title('Posted successfully to ' . implode(', ', array_keys($successful)))
                                ->success()
                                ->send();
                        }

                        if (count($failed) > 0) {
                            $errors = array_map(fn($r) => $r['error'], $failed);
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to post to ' . implode(', ', array_keys($failed)))
                                ->body(implode('; ', $errors))
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (News $record): bool => $record->is_published),

                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (News $record): string => route('news.show', $record->slug))
                    ->openUrlInNewTab()
                    ->visible(fn (News $record): bool => $record->is_published),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_published' => true])))
                        ->requiresConfirmation(),
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
