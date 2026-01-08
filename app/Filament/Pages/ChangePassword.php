<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends Page
{
    protected static string $view = 'filament.pages.change-password';

    protected static ?string $slug = 'change-password';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = null;

    protected static string $layout = 'filament-panels::components.layout.simple';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->label('Current Password')
                    ->password()
                    ->required()
                    ->currentPassword()
                    ->visible(fn () => !auth()->user()->force_password_change),

                TextInput::make('password')
                    ->label('New Password')
                    ->password()
                    ->required()
                    ->rule(Password::default())
                    ->same('password_confirmation')
                    ->validationAttribute('password'),

                TextInput::make('password_confirmation')
                    ->label('Confirm New Password')
                    ->password()
                    ->required()
                    ->dehydrated(false),
            ])
            ->statePath('data');
    }

    public function changePassword(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();

        // Update password
        $user->update([
            'password' => Hash::make($data['password']),
            'force_password_change' => false,
        ]);

        Notification::make()
            ->success()
            ->title('Password changed successfully')
            ->body('Your password has been updated. You can now access all features.')
            ->send();

        // Redirect to dashboard
        $this->redirect('/admin');
    }

    public function getHeading(): string
    {
        return auth()->user()->force_password_change
            ? 'Change Your Password'
            : 'Change Password';
    }

    public function getSubheading(): ?string
    {
        return auth()->user()->force_password_change
            ? 'You must change your password before continuing'
            : null;
    }
}

