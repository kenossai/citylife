<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseEnrollmentResource\Pages;
use App\Filament\Resources\CourseEnrollmentResource\RelationManagers;
use App\Models\CourseEnrollment;
use App\Models\Course;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseEnrollmentResource extends Resource
{
    protected static ?string $model = CourseEnrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Education & Training';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->options(Course::pluck('title', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Member')
                    ->options(Member::selectRaw("id, CONCAT(first_name, ' ', last_name, ' (', email, ')') as full_name")
                        ->pluck('full_name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('enrollment_date')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'dropped' => 'Dropped',
                        'suspended' => 'Suspended',
                    ])
                    ->required()
                    ->default('active'),
                Forms\Components\TextInput::make('progress_percentage')
                    ->label('Progress (%)')
                    ->numeric()
                    ->default(0.00)
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01),
                Forms\Components\TextInput::make('completed_lessons')
                    ->label('Completed Lessons')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\TextInput::make('overall_grade')
                    ->label('Overall Grade')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                Forms\Components\DatePicker::make('completion_date')
                    ->label('Completion Date'),
                Forms\Components\Toggle::make('certificate_issued')
                    ->label('Certificate Issued')
                    ->default(false),
                Forms\Components\TextInput::make('certificate_number')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('payment_info'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.first_name')
                    ->label('Member')
                    ->formatStateUsing(fn ($record) => $record->user->first_name . ' ' . $record->user->last_name)
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->label('Enrolled')
                    ->date()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'primary' => 'completed',
                        'warning' => 'suspended',
                        'danger' => 'dropped',
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_lessons')
                    ->label('Lessons Done')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('overall_grade')
                    ->label('Grade')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : '-')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completion_date')
                    ->label('Completed')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('certificate_issued')
                    ->label('Certificate')
                    ->boolean(),
                Tables\Columns\TextColumn::make('certificate_number')
                    ->label('Cert. Number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCourseEnrollments::route('/'),
            'create' => Pages\CreateCourseEnrollment::route('/create'),
            'edit' => Pages\EditCourseEnrollment::route('/{record}/edit'),
        ];
    }
}
