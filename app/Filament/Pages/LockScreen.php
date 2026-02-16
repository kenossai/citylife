<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LockScreen extends SimplePage
{
    protected static string $view = 'filament.pages.lock-screen';

    protected static bool $shouldRegisterNavigation = false;

    public ?string $password = '';

    public function mount(): void
    {
        if (!session()->has('lock_screen')) {
            $this->redirect(filament()->getUrl());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->autocomplete('current-password')
                    ->autofocus()
                    ->extraInputAttributes(['class' => 'text-center']),
            ])
            ->statePath('data');
    }

    public function unlock(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            Notification::make()
                ->title('Invalid password')
                ->danger()
                ->send();

            $this->password = '';
            return;
        }

        session()->forget('lock_screen');
        session()->regenerate();

        Notification::make()
            ->title('Welcome back!')
            ->success()
            ->send();

        $this->redirect(filament()->getUrl());
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(filament()->getLoginUrl());
    }

    public function getTitle(): string|Htmlable
    {
        return __('Locked');
    }

    public function hasLogo(): bool
    {
        return true;
    }
}
